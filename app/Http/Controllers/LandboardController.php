<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Room;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class LandboardController extends Controller
{
    public function dashboard()
    {
        $landboard = Auth::user()->landboard;

        $totalRooms = Room::where('landboard_id', $landboard->id)->count();

        $totalTenants = Tenant::whereHas('room', function ($query) use ($landboard) {
            $query->where('landboard_id', $landboard->id);
        })->count();

        $occupiedRooms = Room::where('landboard_id', $landboard->id)
                            ->where('status', 'occupied')
                            ->count();

        $monthlyIncome = Payment::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereHas('tenant.room', function ($query) use ($landboard) {
                $query->where('landboard_id', $landboard->id);
            })->sum('amount');

        $yearlyIncome = Payment::whereYear('created_at', now()->year)
            ->whereHas('tenant.room', function ($query) use ($landboard) {
                $query->where('landboard_id', $landboard->id);
            })->sum('amount');

        $recentPayments = Payment::with('tenant')
            ->whereHas('tenant.room', function ($query) use ($landboard) {
                $query->where('landboard_id', $landboard->id);
            })
            ->latest()->take(5)->get();

        $newTenants = Tenant::with(['room', 'rentalHistories'])
            ->whereHas('room', function ($query) use ($landboard) {
                $query->where('landboard_id', $landboard->id);
            })
            ->latest()->take(5)->get();

        $unpaidPayments = Payment::with('tenant')
            ->where('status', 'unpaid')
            ->whereHas('tenant.room', function ($query) use ($landboard) {
                $query->where('landboard_id', $landboard->id);
            })
            ->orderBy('due_date')
            ->get();

        $monthlyLabels = [];
        $monthlyAmounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyLabels[] = Carbon::create()->month($i)->translatedFormat('F');
            $monthlyAmounts[] = Payment::whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->whereHas('tenant.room', function ($query) use ($landboard) {
                    $query->where('landboard_id', $landboard->id);
                })->sum('amount');
        }

        $topRooms = Room::withCount('rentalHistories')
            ->where('landboard_id', $landboard->id)
            ->orderByDesc('rental_histories_count')
            ->take(5)
            ->get();

        return view('landboard.dashboard', compact(
            'totalRooms',
            'totalTenants',
            'occupiedRooms',
            'monthlyIncome',
            'yearlyIncome',
            'recentPayments',
            'newTenants',
            'unpaidPayments',
            'monthlyLabels',
            'monthlyAmounts',
            'topRooms'
        ));
    }

    public function editProfile()
    {
        $account = Auth::user();
        $landboard = $account->landboard;

        return view('landboard.profile.edit', compact('account', 'landboard'));
    }

    public function updateProfile(Request $request)
    {
        $account = Auth::user();
        $landboard = $account->landboard;

        $validated = $request->validate([
            'username'       => 'required|string|min:8|max:20|unique:accounts,username,' . $account->id,
            'email'          => 'required|string|email|unique:accounts,email,' . $account->id,
            'password'       => ['nullable', 'string', 'confirmed', Password::min(6)->letters()->mixedCase()->numbers()],
            'avatar'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'kost_name'      => 'required|string|max:255',
            'province'       => 'required|string|max:100',
            'city'           => 'required|string|max:100',
            'postal_code'    => 'nullable|string|max:10',
            'full_address'   => 'required|string|max:255',
            'bank_name'      => 'required|string|max:100',
            'bank_account'   => 'required|digits_between:10,16',
        ]);

        DB::beginTransaction();

        try {
            /** @var \App\Models\Account $account */
            $account->update([
                'username' => $validated['username'],
                'email'    => $validated['email'],
                'password' => $validated['password'] ?? $account->password,
                'avatar'   => $request->file('avatar')
                    ? $request->file('avatar')->store('avatars', 'public')
                    : $account->avatar,
                'bank_name'     => $validated['bank_name'],
                'bank_account'  => $validated['bank_account'],
            ]);

            $landboard->update([
                'name'          => $validated['name'],
                'phone'         => $validated['phone'],
                'kost_name'     => $validated['kost_name'],
                'province'      => $validated['province'],
                'city'          => $validated['city'],
                'postal_code'   => $validated['postal_code'],
                'full_address'  => $validated['full_address'],
            ]);

            DB::commit();
            return back()->with('success', 'Profil berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }
}