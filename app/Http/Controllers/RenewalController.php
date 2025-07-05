<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\RentalHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RenewalController extends Controller
{
    public function index()
    {
        $landboard = Auth::user()->landboard;

        $requests = RentalHistory::whereHas('room', function ($query) use ($landboard) {
                $query->where('landboard_id', $landboard->id);
            })
            ->with(['tenant.account', 'room'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('landboard.renewal.index', compact('requests'));
    }

    public function directForm()
    {
        $tenant = Auth::user()->tenant;

        $latestActive = RentalHistory::where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->with(['payment', 'room.landboard'])
            ->latest('end_date')
            ->first();

        $canAccessForm = true;
        $canRenew = false;
        $limit = null;
        $history = null;
        $remainingText = null;

        if ($latestActive) {
            $paymentStatus = $latestActive->payment->status ?? null;
            $daysBefore = $latestActive->room->landboard->decision_days_before_end ?? 5;
            $limitDate = Carbon::parse($latestActive->end_date)->subDays($daysBefore);

            $upcoming = RentalHistory::where('tenant_id', $tenant->id)
                ->where('status', 'upcoming')
                ->latest('end_date')
                ->first();

            $canRenew = now()->gte($limitDate);
            $history = $latestActive;
            $limit = $limitDate;

            if ($upcoming) {
                $nextLimit = Carbon::parse($upcoming->end_date)->subDays($daysBefore);
                $canRenew = now()->gte($nextLimit);
                $history = $upcoming;
                $limit = $nextLimit;
            }

            $now = now();
            $end = Carbon::parse($history->end_date);
            $diff = $now->diff($end);

            if ($now->lt($end)) {
                $remainingText = "{$diff->d} hari {$diff->h} jam";
            } else {
                $remainingText = 'Masa sewa telah berakhir';
            }
        }

        return view('tenant.renewal.form', [
            'history' => $history,
            'can_renew' => $canRenew,
            'limit_date' => $limit,
            'remaining_time' => $remainingText,
        ]);
    }

    public function store(Request $request, $id)
    {
        $tenant = Auth::user()->tenant;

        $validated = $request->validate([
            'duration' => ['required', function ($attribute, $value, $fail) {
                $allowed = ['0.1', '1', '3', '6', '12'];
                if (!in_array((string) $value, $allowed)) {
                    $fail('Durasi tidak valid.');
                }
            }],
        ]);

        $previousHistory = RentalHistory::where('id', $id)
            ->where('tenant_id', $tenant->id)
            ->with('payment', 'room.landboard')
            ->firstOrFail();

        if (!$previousHistory->payment || $previousHistory->payment->status !== 'paid') {
            return redirect()->back()->with('error', 'Sewa sebelumnya belum lunas.');
        }

        $existing = RentalHistory::where('tenant_id', $tenant->id)
            ->where('status', '!=', 'ended')
            ->where('start_date', '>=', $previousHistory->end_date)
            ->exists();

        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah mengajukan atau memiliki perpanjangan aktif.');
        }

        $pricePerMonth = $previousHistory->room->price;
        if (!$pricePerMonth || $pricePerMonth <= 0) {
            return redirect()->back()->with('error', 'Harga kamar belum ditentukan. Hubungi pemilik kos.');
        }

        $startDate = Carbon::parse($previousHistory->end_date);
        $duration = (float) $validated['duration'];

        $endDate = ($duration === 0.1)
            ? (clone $startDate)->addDays(5)
            : (clone $startDate)->addMonths((int) $duration);

        DB::beginTransaction();
        try {
            $rental = RentalHistory::create([
                'tenant_id' => $tenant->id,
                'room_id' => $previousHistory->room_id,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'duration_months' => $duration,
                'status' => 'upcoming',
                'decision_status' => 'extend',
            ]);

            Payment::create([
                'tenant_id' => $tenant->id,
                'rental_history_id' => $rental->id,
                'amount' => $pricePerMonth * $duration,
                'status' => 'unpaid',
                'due_date' => now()->addDays($previousHistory->room->landboard->late_fee_days ?? 3),
            ]);

            DB::commit();
            return redirect()->route('tenant.dashboard.index')->with('success', 'Perpanjangan berhasil dibuat dan menunggu pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal perpanjangan otomatis', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan. Coba lagi nanti.');
        }
    }
}
