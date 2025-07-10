<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Payment;
use App\Models\RentalHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function showPaymentList()
    {
        $tenant = Auth::user()->tenant;

        $payments = Payment::with(['rentalHistory.room'])
            ->where('tenant_id', $tenant->id)
            ->orderByDesc('created_at')
            ->get();

        $channels = $this->getTripayChannels();

        return view('tenant.payment.index', compact('payments', 'channels'));
    }

    private function getTripayChannels()
    {
        return [
            ['code' => 'BRIVA', 'name' => 'BRI VA', 'group' => 'Bank Transfer'],
            ['code' => 'BNIVA', 'name' => 'BNI VA', 'group' => 'Bank Transfer'],
            ['code' => 'MANDIRIVA', 'name' => 'Mandiri VA', 'group' => 'Bank Transfer'],
            ['code' => 'BCAVA', 'name' => 'BCA VA', 'group' => 'Bank Transfer'],
            ['code' => 'PERMATAVA', 'name' => 'Permata VA', 'group' => 'Bank Transfer'],
            ['code' => 'MUAMALATVA', 'name' => 'Muamalat VA', 'group' => 'Bank Transfer'],
            ['code' => 'QRIS', 'name' => 'QRIS', 'group' => 'QR Code'],
            ['code' => 'ALFAMART', 'name' => 'Alfamart', 'group' => 'Retail'],
            ['code' => 'INDOMARET', 'name' => 'Indomaret', 'group' => 'Retail'],
        ];
    }

    public function createInvoice(Request $request, $rentalId = null)
    {
        $request->validate([
            'payment_method' => 'required|string'
        ]);

        $tenant = Auth::user();
        $paymentMethod = $request->input('payment_method');

        $rental = $rentalId
            ? RentalHistory::with(['room.landboard'])->findOrFail($rentalId)
            : RentalHistory::with(['room.landboard'])
                ->where('tenant_id', $tenant->tenant->id)
                ->latest()->first();

        if (!$rental || !$rental->room || !$rental->room->landboard) {
            return redirect()->back()->with('error', 'Data sewa belum lengkap.');
        }

        $room = $rental->room;
        $landboard = $room->landboard;

        $baseAmount = $room->price * $rental->duration_months;
        $startDate = Carbon::parse($rental->start_date);
        $deadline = $startDate->copy()->addDays($landboard->late_fee_days ?? 0);

        $lateFee = 0;
        if ($landboard->is_penalty_enabled && now()->gt($deadline)) {
            $lateFee = $landboard->late_fee_amount ?? 0;
        }

        $totalAmount = $baseAmount + $lateFee;
        $amountInt = (int) $totalAmount;
        $dueDate = now()->addDays(7);
        $orderId = config('services.tripay.merchant_ref_prefix', 'INV-') . strtoupper(Str::random(10));

        $payment = Payment::updateOrCreate(
            [
                'rental_history_id' => $rental->id,
                'status' => 'unpaid',
            ],
            [
                'tenant_id'      => $rental->tenant_id,
                'room_id'        => $room->id,
                'landboard_id'   => $landboard->id,
                'amount'         => $baseAmount,
                'penalty_amount' => $lateFee,
                'total_amount'   => $totalAmount,
                'invoice_id'     => $orderId,
                'due_date'       => $dueDate,
                'paid_at'        => null,
            ]
        );

        $merchantCode = config('services.tripay.merchant_code');
        $privateKey = config('services.tripay.private_key');
        $apiKey = config('services.tripay.api_key');
        $signature = hash_hmac('sha256', $merchantCode . $orderId . $amountInt, $privateKey);

        $tripayUrl = config('services.tripay.is_production', false)
            ? 'https://tripay.co.id/api/transaction/create'
            : 'https://tripay.co.id/api-sandbox/transaction/create';

        $callbackUrl = config('services.tripay.callback_url', 'https://example.com/api/tripay/callback');

        $requestData = [
            'method'         => $paymentMethod,
            'merchant_ref'   => $orderId,
            'amount'         => $amountInt,
            'customer_name'  => $tenant->name ?? $tenant->email ?? 'Tenant',
            'customer_email' => $tenant->email,
            'order_items'    => [
                [
                    'name'     => 'Sewa kamar ' . $room->room_number,
                    'price'    => $amountInt,
                    'quantity' => 1
                ]
            ],
            'callback_url'   => $callbackUrl,
            'return_url'     => route('tenant.dashboard.index'),
            'expired_time'   => $dueDate->timestamp,
            'signature'      => $signature,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey
        ])->post($tripayUrl, $requestData);

        $responseData = $response->json();

        if ($response->successful() && isset($responseData['data']['checkout_url'])) {
            $payment->update([
                'external_id' => $responseData['data']['reference'],
                'tripay_reference' => $responseData['data']['reference'],
            ]);

            return redirect()->away($responseData['data']['checkout_url']);
        }

        $errorMessage = $responseData['message'] ?? 'Gagal membuat invoice.';
        return redirect()->back()->with('error', $errorMessage);
    }

    public function handleTripayCallback(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        $event = $request->header('X-Callback-Event');

        if (!$json || !$event) {
            return response()->json(['error' => 'Invalid callback data'], 400);
        }

        if ($event === 'payment_status' && isset($json['status']) && $json['status'] === 'PAID') {
            $reference    = $json['reference'] ?? null;
            $merchant_ref = $json['merchant_ref'] ?? null;

            $payment = Payment::where('tripay_reference', $reference)
                ->orWhere('invoice_id', $merchant_ref)
                ->first();

            if ($payment && $payment->status !== 'paid') {
                $payment->update([
                    'status'         => 'paid',
                    'paid_at'        => now(),
                    'payment_method' => $json['payment_method'] ?? 'Tripay',
                ]);
            }
        }
        
        return response()->json(['success' => true], 200);
    }

    public function checkStatus()
    {
        $tenant = Auth::user();
        $rental = RentalHistory::where('tenant_id', $tenant->tenant->id)->latest()->first();

        if (!$rental) {
            return response()->json(['status' => 'no-rental']);
        }

        $payment = Payment::where('rental_history_id', $rental->id)->latest()->first();

        return response()->json([
            'status'     => $payment ? $payment->status : 'not-found',
            'invoice_id' => $payment->invoice_id ?? null,
            'paid_at'    => $payment->paid_at ?? null,
        ]);
    }

    public function markAsPaid(Payment $payment)
    {
        $payment->update([
            'status'         => 'paid',
            'paid_at'        => now(),
            'payment_method' => 'Cash'
        ]);

        return redirect()->back()->with('success', 'Payment marked as paid successfully');
    }

    public function cashIndex(Request $request)
    {
        $payments = Payment::with(['rentalHistory.room', 'tenant.account'])
            ->where('status', 'unpaid')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($subquery) use ($search) {
                    $subquery->whereHas('tenant', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                        ->orWhereHas('account', function ($qq) use ($search) {
                            $qq->where('username', 'like', "%$search%");
                        });
                    })->orWhereHas('rentalHistory.room', function ($q) use ($search) {
                        $q->where('room_number', 'like', "%$search%");
                    });
                });
            })
            ->when($request->filled('sort'), function ($query) use ($request) {
                switch ($request->sort) {
                    case 'due_date_asc':
                        $query->orderBy('due_date', 'asc');
                        break;
                    case 'due_date_desc':
                        $query->orderBy('due_date', 'desc');
                        break;
                    case 'amount_asc':
                        $query->orderBy('amount', 'asc');
                        break;
                    case 'amount_desc':
                        $query->orderBy('amount', 'desc');
                        break;
                    case 'username_asc':
                        $query->orderBy(
                            Account::select('username')
                                ->whereColumn('accounts.id', 'tenants.account_id'),
                            'asc'
                        );
                        break;
                    case 'username_desc':
                        $query->orderBy(
                            Account::select('username')
                                ->whereColumn('accounts.id', 'tenants.account_id'),
                            'desc'
                        );
                        break;
                    default:
                        $query->orderBy('created_at', 'desc'); 
                        break;
                }
            }, function ($query) {
                $query->orderBy('created_at', 'desc'); 
            })
            ->get();

        return view('landboard.payment.index', compact('payments'));
    }

    public function history()
    {
        $tenant = Auth::user()->tenant;

        $payments = Payment::with(['rentalHistory.room'])
            ->where('tenant_id', $tenant->id)
            ->where('status', 'paid')
            ->orderByDesc('paid_at')
            ->get();

        return view('tenant.payment.history', compact('payments'));
    }
}