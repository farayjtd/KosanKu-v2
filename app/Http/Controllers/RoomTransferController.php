<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RoomTransferRequest;
use App\Models\RentalHistory;
use App\Models\Room;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RoomTransferController extends Controller
{
    public function showForm()
    {
        $tenant = Auth::user()->tenant;

        $currentHistory = RentalHistory::with('room')
            ->where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$currentHistory) {
            return back()->with('error', 'Kamu belum memiliki kamar aktif.');
        }

        $room = $currentHistory->room;
        $now = now();
        $startDate = Carbon::parse($currentHistory->start_date);
        $endDate = Carbon::parse($currentHistory->end_date);

        $totalHours = $now->diffInHours($endDate, false);
        $diffDays = floor($totalHours / 24);
        $diffHours = $totalHours % 24;
        $daysLeft = $totalHours <= 0
            ? 'Masa sewa telah berakhir'
            : trim(($diffDays > 0 ? $diffDays . ' hari ' : '') . ($diffHours > 0 ? $diffHours . ' jam' : ''));

        $totalDays = $startDate->diffInDays($endDate);
        $totalPrice = $room->price * $currentHistory->duration_months;
        $dailyRate = $totalPrice / $totalDays;

        $landboard = $room->landboard;
        $penalty = $landboard->room_change_penalty_amount ?? 0;
        $daysPassed = $startDate->diffInDays($now);
        $refundAmount = max(0, (($totalDays - $daysPassed) * $dailyRate) - $penalty);

        $latestUnpaid = Payment::where('tenant_id', $tenant->id)
            ->where('rental_history_id', $currentHistory->id)
            ->where('status', 'unpaid')
            ->first();

        $canTransfer = !$latestUnpaid;

        $availableRooms = Room::where('status', 'available')
            ->where('landboard_id', $room->landboard_id)
            ->where('id', '!=', $room->id)
            ->get();

        $latestRequest = RoomTransferRequest::where('tenant_id', $tenant->id)
            ->latest()
            ->first();

        return view('tenant.room-transfer.form', compact(
            'currentHistory',
            'daysLeft',
            'refundAmount',
            'availableRooms',
            'latestRequest',
            'canTransfer'
        ));
    }

    public function process(Request $request)
    {
        $tenant = Auth::user()->tenant;

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ]);

        $existing = RoomTransferRequest::where('tenant_id', $tenant->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah memiliki permintaan yang masih diproses.');
        }

        $currentHistory = $tenant->rentalHistories()
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$currentHistory) {
            return back()->with('error', 'Tidak ditemukan histori sewa aktif.');
        }

        $unpaidPayment = Payment::where('tenant_id', $tenant->id)
            ->where('rental_history_id', $currentHistory->id)
            ->where('status', '!=', 'paid')
            ->first();

        if ($unpaidPayment) {
            return back()->with('error', 'Silakan selesaikan pembayaran kamar saat ini sebelum mengajukan pindah.');
        }

        $room = $currentHistory->room;
        $now = now();
        $startDate = Carbon::parse($currentHistory->start_date);
        $endDate = Carbon::parse($currentHistory->end_date);
        $totalHours = $now->diffInHours($endDate, false);
            $diffDays = floor($totalHours / 24);
            $diffHours = $totalHours % 24;
            $daysLeft = $totalHours <= 0
                ? 'Masa sewa telah berakhir'
                : trim(($diffDays > 0 ? $diffDays . ' hari ' : '') . ($diffHours > 0 ? $diffHours . ' jam' : ''));

        $totalDays = $startDate->diffInDays($endDate);
        $totalPrice = $room->price * $currentHistory->duration_months;
        $dailyRate = $totalPrice / $totalDays;

        $landboard = $room->landboard;
        $penalty = $landboard->room_change_penalty_amount ?? 0;
        $daysPassed = $startDate->diffInDays($now);
        $refundAmount = max(0, (($totalDays - $daysPassed) * $dailyRate) - $penalty);

        RoomTransferRequest::create([
            'tenant_id'       => $tenant->id,
            'current_room_id' => $currentHistory->room_id,
            'new_room_id'     => $request->room_id,
            'status'          => 'pending',
            'manual_refund'   => $refundAmount,
        ]);

        return back()->with('success', 'Permintaan pindah kamar berhasil diajukan.');
    }

    public function index()
    {
        $landboard = Auth::user()->landboard;

        $requests = RoomTransferRequest::with(['tenant', 'currentRoom', 'newRoom'])
            ->whereHas('currentRoom', function ($query) use ($landboard) {
                $query->where('landboard_id', $landboard->id);
            })
            ->latest('created_at')
            ->get();

        return view('landboard.room-transfer.index', compact('requests'));
    }

    public function handleAction(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|string|max:1000',
            'action_type' => 'required|in:approve,reject',
        ]);

        $transfer = RoomTransferRequest::with(['tenant', 'currentRoom', 'newRoom'])->findOrFail($id);

        if ($transfer->status !== 'pending') {
            return back()->with('error', 'Permintaan sudah diproses.');
        }

        if ($request->action_type === 'approve') {
            DB::beginTransaction();
            try {
                $transfer->status = 'approved';
                $transfer->note = $request->note;
                $transfer->save();

                $currentHistory = RentalHistory::where('tenant_id', $transfer->tenant_id)
                    ->where('room_id', $transfer->current_room_id)
                    ->where('status', 'active')
                    ->first();

                if ($currentHistory) {
                    $currentHistory->update(['status' => 'transferred']);
                }

                $transfer->currentRoom->update(['status' => 'available']);
                $transfer->newRoom->update(['status' => 'occupied']);

                $tenant = $transfer->tenant;
                $tenant->room_id = $transfer->new_room_id;
                $tenant->save();

                $startDate = now();
                $endDate = (clone $startDate)->addMonth();

                $newHistory = RentalHistory::create([
                    'tenant_id'       => $transfer->tenant_id,
                    'room_id'         => $transfer->new_room_id,
                    'start_date'      => $startDate,
                    'end_date'        => $endDate,
                    'duration_months' => 1,
                    'status'          => 'active',
                ]);

                Payment::create([
                    'rental_history_id' => $newHistory->id,
                    'tenant_id'         => $transfer->tenant_id,
                    'amount'            => $transfer->newRoom->price,
                    'status'            => 'unpaid',
                    'due_date'          => $startDate,
                    'is_penalty'        => $transfer->currentRoom->landboard->is_penalty_on_room_change,
                    'description'       => 'Tagihan pindah kamar',
                    'type'              => 'income',
                ]);

                Payment::create([
                    'rental_history_id' => $currentHistory?->id,
                    'tenant_id'         => $transfer->tenant_id,
                    'amount'            => $transfer->manual_refund,
                    'status'            => 'paid',
                    'due_date'          => now(),
                    'is_penalty'        => false,
                    'description'       => 'Refund sisa sewa karena pindah kamar',
                    'type'              => 'expense',
                ]);

                DB::commit();
                return back()->with('success', 'Permintaan disetujui.');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Terjadi kesalahan saat menyetujui permintaan.');
            }
        }

        if ($request->action_type === 'reject') {
            $transfer->status = 'rejected';
            $transfer->manual_refund = 0;
            $transfer->note = $request->note;
            $transfer->save();

            return back()->with('success', 'Permintaan ditolak.');
        }

        return back()->with('error', 'Aksi tidak dikenali.');
    }
}
