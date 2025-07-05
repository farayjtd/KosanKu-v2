<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\RentalHistory;
use App\Models\Room;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RentalHistoryController extends Controller
{
    public function index()
    {
        $tenant = Auth::user()->tenant;

        $histories = RentalHistory::with(['room', 'payment'])
            ->where('tenant_id', $tenant->id)
            ->orderByDesc('start_date')
            ->get();

        $today = Carbon::now()->timezone('Asia/Jakarta')->startOfDay();

        foreach ($histories as $history) {
            $start = Carbon::parse($history->start_date)->timezone('Asia/Jakarta')->startOfDay();
            $end = $history->end_date ? Carbon::parse($history->end_date)->timezone('Asia/Jakarta')->startOfDay() : null;

            if ($start->gt($today)) {
                $history->computed_status = 'Belum Dimulai';
            } elseif ($end && $end->lt($today)) {
                $history->computed_status = 'Selesai';
            } else {
                $history->computed_status = 'Sedang Berjalan';
            }
        }

        return view('tenant.room-history.index', compact('histories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenant_id'        => 'required|exists:tenants,id',
            'room_id'          => 'required|exists:rooms,id',
            'start_date'       => 'required|date|after_or_equal:today',
            'duration_months'  => 'required|integer|min:1|max:24',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate   = $startDate->copy()->addMonths($request->duration_months);

        DB::beginTransaction();

        try {
            $status = $startDate->isFuture() ? 'upcoming' : 'active';

            $history = RentalHistory::create([
                'tenant_id'       => $request->tenant_id,
                'room_id'         => $request->room_id,
                'start_date'      => $startDate,
                'end_date'        => $endDate,
                'duration_months' => $request->duration_months,
                'status'          => $status,
            ]);

            $room = Room::findOrFail($request->room_id);
            $room->status = 'booked'; 
            $room->save();

            $tenant = Tenant::findOrFail($request->tenant_id);
            $total  = $room->price * $request->duration_months;

            Payment::create([
                'tenant_id'         => $tenant->id,
                'rental_history_id' => $history->id,
                'amount'            => $total,
                'description'       => 'Pembayaran sewa awal',
                'due_date'          => $startDate,
                'status'            => 'unpaid',
                'is_penalty'        => false,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Histori sewa berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function end($id)
    {
        $history = RentalHistory::with('room')->findOrFail($id);

        $history->update([
            'status'   => 'ended',
            'end_date' => Carbon::now(),
        ]);

        $room = $history->room;
        if ($room) {
            $room->status = 'available';
            $room->save();
        }

        return redirect()->back()->with('success', 'Sewa telah diakhiri.');
    }

    public function landboardIndex(Request $request)
    {
        $landboard = Auth::user()->landboard;

        $sort = $request->get('sort', 'start_desc'); 
        
        $sortMap = [
            'start_asc'   => ['start_date', 'asc'],
            'start_desc'  => ['start_date', 'desc'],
            'tenant_asc'  => ['tenants.name', 'asc'],
            'tenant_desc' => ['tenants.name', 'desc'],
            'room_asc'    => ['rooms.room_number', 'asc'],
            'room_desc'   => ['rooms.room_number', 'desc'],
        ];

        [$sortField, $sortOrder] = $sortMap[$sort] ?? ['start_date', 'desc'];

        $histories = RentalHistory::with(['room', 'tenant'])
            ->whereHas('room', fn($q) => $q->where('landboard_id', $landboard->id))
            ->join('tenants', 'rental_histories.tenant_id', '=', 'tenants.id')
            ->join('rooms', 'rental_histories.room_id', '=', 'rooms.id')
            ->orderBy($sortField, $sortOrder)
            ->select('rental_histories.*') 
            ->get();

        $today = Carbon::now()->timezone('Asia/Jakarta')->startOfDay();

        foreach ($histories as $history) {
            $start = Carbon::parse($history->start_date)->timezone('Asia/Jakarta')->startOfDay();
            $end   = $history->end_date 
                ? Carbon::parse($history->end_date)->timezone('Asia/Jakarta')->startOfDay()
                : null;

            if ($start->gt($today)) {
                $history->computed_status = 'Belum Dimulai';
            } elseif ($end && $end->lt($today)) {
                $history->computed_status = 'Selesai';
            } else {
                $history->computed_status = 'Sedang Berjalan';
            }

            $history->tenant_name = $history->tenant->name ?? '[Tenant tidak tersedia]';
            $history->room_name   = $history->room->room_number ?? '[Kamar tidak tersedia]';
        }

        return view('landboard.rental-history.index', compact('histories', 'sort'));
    }
}