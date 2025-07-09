<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $landboard = Auth::user()->landboard;

        $selectedMonth = $request->month ?? 'all';
        $sort = $request->sort ?? null;
        $keyword = $request->search;

        $usernames = $landboard->rooms()
            ->with('rentalHistories.tenant.account')
            ->get()
            ->pluck('rentalHistories')
            ->flatten()
            ->pluck('tenant.account.username')
            ->unique()
            ->filter()
            ->values();

        $incomeQuery = Payment::where('type', 'income')
            ->where('status', 'paid')
            ->whereHas('rentalHistory.room', function ($q) use ($landboard) {
                $q->where('landboard_id', $landboard->id);
            })
            ->when($selectedMonth !== 'all', function ($q) use ($selectedMonth) {
                $q->whereMonth('paid_at', $selectedMonth);
            })
            ->when($keyword, function ($q) use ($keyword) {
                $q->where(function ($qq) use ($keyword) {
                    $qq->whereHas('rentalHistory.tenant', fn ($tq) =>
                            $tq->where('name', 'like', "%$keyword%")
                        )
                        ->orWhereHas('rentalHistory.tenant.account', fn ($aq) =>
                            $aq->where('username', 'like', "%$keyword%")
                        )
                        ->orWhereHas('rentalHistory.room', fn ($rq) =>
                            $rq->where('room_number', 'like', "%$keyword%")
                        )
                        ->orWhere('payment_method', 'like', "%$keyword%");
                });
            });

        switch ($sort) {
            case 'amount_desc':
                $incomeQuery->orderByDesc('amount');
                break;
            case 'amount_asc':
                $incomeQuery->orderBy('amount');
                break;
            case 'date_asc':
                $incomeQuery->orderBy('paid_at');
                break;
            case 'date_desc':
            default:
                $incomeQuery->orderByDesc('paid_at');
                break;
        }

        $incomePayments = $incomeQuery->get();

        $expenseQuery = Payment::where('type', 'expense')
            ->where('status', 'paid')
            ->whereHas('rentalHistory.room', function ($q) use ($landboard) {
                $q->where('landboard_id', $landboard->id);
            })
            ->when($selectedMonth !== 'all', function ($q) use ($selectedMonth) {
                $q->whereMonth('paid_at', $selectedMonth);
            })
            ->when($keyword, function ($q) use ($keyword) {
                $q->where(function ($qq) use ($keyword) {
                    $qq->whereHas('rentalHistory.tenant', fn ($tq) =>
                            $tq->where('name', 'like', "%$keyword%")
                        )
                        ->orWhereHas('rentalHistory.tenant.account', fn ($aq) =>
                            $aq->where('username', 'like', "%$keyword%")
                        )
                        ->orWhereHas('rentalHistory.room', fn ($rq) =>
                            $rq->where('room_number', 'like', "%$keyword%")
                        )
                        ->orWhere('payment_method', 'like', "%$keyword%");
                });
            });

        switch ($sort) {
            case 'amount_desc':
                $expenseQuery->orderByDesc('amount');
                break;
            case 'amount_asc':
                $expenseQuery->orderBy('amount');
                break;
            case 'date_asc':
                $expenseQuery->orderBy('paid_at');
                break;
            case 'date_desc':
            default:
                $expenseQuery->orderByDesc('paid_at');
                break;
        }

        $expensePayments = $expenseQuery->get();

        return view('landboard.finance.index', compact(
            'incomePayments',
            'expensePayments',
            'selectedMonth',
            'keyword',
            'usernames',
            'sort'
        ));
    }
}
