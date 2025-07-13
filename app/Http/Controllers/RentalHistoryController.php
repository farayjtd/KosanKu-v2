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

    public function landboardIndex(Request $request)
    {
        $landboard = Auth::user()->landboard;
        $keyword = $request->search;

        $histories = RentalHistory::with(['room', 'tenant.account'])
            ->whereHas('room', function ($q) use ($landboard) {
                $q->where('landboard_id', $landboard->id);
            })
            ->orderByDesc('start_date')
            ->get();

        $today = now()->timezone('Asia/Jakarta')->startOfDay();

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

        if ($keyword) {
            $histories = $histories->filter(function ($history) use ($keyword) {
                return stripos($history->tenant->name ?? '', $keyword) !== false
                    || stripos($history->room->room_number ?? '', $keyword) !== false
                    || stripos($history->computed_status ?? '', $keyword) !== false;
            })->values(); 
        }
        return view('landboard.rental-history.index', compact('histories', 'keyword'));
    }
}