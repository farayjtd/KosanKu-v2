<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Payment;
use App\Models\RentalHistory;
use App\Models\Room;
use App\Models\RoomTransferRequest;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            ->where('status', 'paid')
            ->whereHas('tenant.room', function ($query) use ($landboard) {
                $query->where('landboard_id', $landboard->id);
            })->sum('amount');

        $yearlyIncome = Payment::whereYear('created_at', now()->year)
            ->where('status', 'paid')
            ->whereHas('tenant.room', function ($query) use ($landboard) {
                $query->where('landboard_id', $landboard->id);
            })->sum('amount');

        $monthlyExpense = RoomTransferRequest::where('status', 'approved')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->whereHas('tenant.room', function ($query) use ($landboard) {
                $query->where('landboard_id', $landboard->id);
            })->sum('manual_refund');

        $yearlyExpense = RoomTransferRequest::where('status', 'approved')
            ->whereYear('updated_at', now()->year)
            ->whereHas('tenant.room', function ($query) use ($landboard) {
                $query->where('landboard_id', $landboard->id);
            })->sum('manual_refund');

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
        for ($i = 1; $i <= 12; $i++) {
            $monthlyLabels[] = Carbon::create()->month($i)->translatedFormat('F');
        }

        $monthlyAmounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyAmounts[] = Payment::whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->where('status', 'paid')
                ->whereHas('tenant.room', function ($query) use ($landboard) {
                    $query->where('landboard_id', $landboard->id);
                })->sum('amount');
        }

        $monthlyExpenseAmounts = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyExpenseAmounts[] = RoomTransferRequest::where('status', 'approved')
                ->whereMonth('updated_at', $i)
                ->whereYear('updated_at', now()->year)
                ->whereHas('tenant.room', function ($query) use ($landboard) {
                    $query->where('landboard_id', $landboard->id);
                })
                ->sum('manual_refund');
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
            'monthlyExpense',
            'yearlyExpense',
            'recentPayments',
            'newTenants',
            'unpaidPayments',
            'monthlyLabels',
            'monthlyAmounts',
            'monthlyExpenseAmounts',
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

    public function showCreateTenantForm()
    {
        $rooms = Room::where('status', 'available')->get();
        return view('landboard.tenant.create', compact('rooms'));
    }

    public function storeNewTenant(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:6|max:20|unique:accounts,username',
            'password' => ['required', 'confirmed', Password::min(6)->letters()->mixedCase()->numbers()],
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'duration_months' => 'required|numeric|min:0.1',

        ]);

        DB::beginTransaction();
        try {
            $account = Account::create([
                'username' => $request->username,
                'password' => $request->password,
                'role' => 'tenant',
                'is_first_login' => true,
            ]);

            $room = Room::findOrFail($request->room_id);

            $tenant = Tenant::create([
                'account_id' => $account->id,
                'room_id' => $room->id,
                'status' => 'aktif',
            ]);

            $startDate = Carbon::parse($request->start_date);
            $duration = floatval($request->duration_months);

            $endDate = $duration === 0.1 ? $startDate->copy()->addDays(5) : $startDate->copy()->addMonths($duration);
            $chargeMonths = $duration === 0.1 ? 1 : $duration;

            $room->status = $startDate->greaterThan(Carbon::today()) ? 'booked' : 'occupied';
            $room->save();

            $rental = RentalHistory::create([
                'tenant_id' => $tenant->id,
                'room_id' => $room->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'duration_months' => $duration,
                'status' => 'active',
            ]);

            Payment::create([
                'tenant_id' => $tenant->id,
                'rental_history_id' => $rental->id,
                'amount' => $room->price * $chargeMonths,
                'description' => 'Tagihan awal sewa kamar',
                'due_date' => $startDate,
                'status' => 'unpaid',
                'is_penalty' => false,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Akun tenant dan data sewa berhasil dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal membuat tenant: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $tenant = Tenant::with(['account', 'room.landboard'])->findOrFail($id);

        return view('landboard.tenant.show', compact('tenant'));
    }

    public function showEditTenantForm($id)
    {
        $tenant = Tenant::with('account')->findOrFail($id);
        return view('landboard.tenant.edit', compact('tenant'));
    }

    public function updateTenantData(Request $request, $id)
    {
        $tenant = Tenant::with('account')->findOrFail($id);
        $account = $tenant->account;

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'address'          => 'required|string|max:255',
            'gender'           => 'nullable|in:male,female',
            'activity_type'    => 'nullable|string|max:255',
            'institution_name' => 'nullable|string|max:255',
            'bank_name'        => 'required|string|max:100',
            'bank_account'     => 'required|digits_between:10,16',
            'email'            => 'nullable|email|unique:accounts,email,' . $account->id,
            'username'         => 'required|string|max:100|unique:accounts,username,' . $account->id,
            'password'         => 'nullable|confirmed|min:8',
            'avatar'           => 'nullable|image|max:2048',
            'identity_photo'   => 'nullable|image|max:2048',
            'selfie_photo'     => 'nullable|image|max:2048',
        ]);

        $tenant->fill([
            'name'             => $validated['name'],
            'phone'            => $validated['phone'],
            'address'          => $validated['address'],
            'gender'           => $validated['gender'],
            'activity_type'    => $validated['activity_type'],
            'institution_name' => $validated['institution_name'],
        ]);

        $account->bank_name    = $validated['bank_name'];
        $account->bank_account = $validated['bank_account'];

        if (!empty($validated['email'])) {
            $account->email = $validated['email'];
        }

        if (!empty($validated['username'])) {
            $account->username = $validated['username'];
        }

        if (!empty($validated['password'])) {
            $account->password = $validated['password'];
        }

        if ($request->hasFile('avatar')) {
            if ($account->avatar) {
                Storage::disk('public')->delete($account->avatar);
            }
            $account->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->hasFile('identity_photo')) {
            if ($tenant->identity_photo) {
                Storage::disk('public')->delete($tenant->identity_photo);
            }
            $tenant->identity_photo = $request->file('identity_photo')->store('tenant_identities', 'public');
        }

        if ($request->hasFile('selfie_photo')) {
            if ($tenant->selfie_photo) {
                Storage::disk('public')->delete($tenant->selfie_photo);
            }
            $tenant->selfie_photo = $request->file('selfie_photo')->store('tenant_selfies', 'public');
        }

        $tenant->save();
        $account->save();

        return redirect()->route('landboard.tenants.index')->with('success', 'Data tenant berhasil diperbarui.');
    }

    public function showReactivateForm($id)
    {
        $tenant = Tenant::findOrFail($id);
        $rooms = Room::where('status', 'available')->get();

        return view('landboard.tenant.reactivate', compact('tenant', 'rooms')); 
    }
    
    public function reactivateTenant(Request $request, $id)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'duration_months' => 'required|numeric|min:0.1',
        ]);

        DB::beginTransaction();
        try {
            $tenant = Tenant::findOrFail($id);
            $room = Room::findOrFail($request->room_id);

            $tenant->update([
                'status' => 'aktif',
                'room_id' => $room->id,
            ]);

            $startDate = Carbon::parse($request->start_date);
            $duration = floatval($request->duration_months);
            $endDate = $duration === 0.1 ? $startDate->copy()->addDays(5) : $startDate->copy()->addMonths($duration);
            $chargeMonths = $duration === 0.1 ? 1 : $duration;

            $room->status = $startDate->greaterThan(Carbon::today()) ? 'booked' : 'occupied';
            $room->save();

            $rental = RentalHistory::create([
                'tenant_id' => $tenant->id,
                'room_id' => $room->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'duration_months' => $duration,
                'status' => 'active',
            ]);

            Payment::create([
                'tenant_id' => $tenant->id,
                'rental_history_id' => $rental->id,
                'amount' => $room->price * $chargeMonths,
                'description' => 'Tagihan aktivasi ulang',
                'due_date' => $startDate,
                'status' => 'unpaid',
                'is_penalty' => false,
            ]);

            DB::commit();
            return redirect()->route('landboard.tenants.index')->with('success', 'Tenant berhasil diaktifkan kembali.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal mengaktifkan tenant: ' . $e->getMessage()]);
        }
    }

    public function deactivateTenant($id)
    {
        $tenant = Tenant::findOrFail($id);
        $room = $tenant->room;

        $tenant->status = 'nonaktif';
        $tenant->save();

        if ($room) {
            $room->status = 'available';
            $room->save();
        }

        $activeRental = $tenant->rentalHistories()
            ->where('status', 'active')
            ->latest('end_date')
            ->first();

        if ($activeRental) {
            $today = now();
            $endDate = Carbon::parse($activeRental->end_date);

            $landboard = $room->landboard;
            $moveoutPenalty = $landboard->is_penalty_on_moveout ? ($landboard->moveout_penalty_amount ?? 0) : 0;

            $refundAmount = 0;
            if ($today->lt($endDate)) {
                $remainingDays = $today->diffInDays($endDate);

                $pricePerMonth = $room->price;
                $dailyRate = $pricePerMonth / 30;
                $refundAmount = round($dailyRate * $remainingDays, 0);
            }

            Payment::create([
                'tenant_id' => $tenant->id,
                'rental_history_id' => $activeRental->id,
                'amount' => $refundAmount,
                'status' => 'paid',
                'due_date' => now(),
                'is_penalty' => false,
                'description' => 'Refund sisa sewa karena keluar kosan',
                'type' => 'expense',
            ]);

            if ($moveoutPenalty > 0) {
                Payment::create([
                    'tenant_id' => $tenant->id,
                    'rental_history_id' => $activeRental->id,
                    'amount' => $moveoutPenalty,
                    'status' => 'unpaid',
                    'due_date' => now(),
                    'is_penalty' => true,
                    'description' => 'Denda keluar sebelum kontrak selesai',
                    'type' => 'income',
                ]);
            }

            $activeRental->status = 'ended';
            $activeRental->save();
        }

        return redirect()->route('landboard.tenants.index')
            ->with('success', 'Tenant berhasil dinonaktifkan. Denda keluar kos dan refund telah dihitung otomatis.');
    }
}