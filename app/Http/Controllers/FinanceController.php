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
        $username = $request->username ?? null;
        $sort = $request->sort ?? null;

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
            ->when($username, function ($q) use ($username) {
                $q->whereHas('tenant.account', function ($qq) use ($username) {
                    $qq->where('username', 'like', "%$username%");
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
            ->when($username, function ($q) use ($username) {
                $q->whereHas('tenant.account', function ($qq) use ($username) {
                    $qq->where('username', 'like', "%$username%");
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
            'username',
            'usernames',
            'sort'
        ));
    }
}
