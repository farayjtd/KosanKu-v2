<?php

namespace App\Http\Controllers;

use App\Models\{Account, Payment, RentalHistory, Room, RoomTransferRequest, Tenant};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Hash, Storage};
use Illuminate\Validation\Rules\Password;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::with(['account', 'room']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhereHas('account', fn ($qq) => 
                        $qq->where('username', 'like', "%$search%")
                    );
            });
        }

        if ($request->filled('status') && in_array($request->status, ['aktif', 'nonaktif'])) {
            $query->where('status', $request->status);
        }

        $sort = $request->get('sort', 'name_asc');
        switch ($sort) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'username_asc':
                $query->orderBy(Account::select('username')->whereColumn('accounts.id', 'tenants.account_id'), 'asc');
                break;
            case 'username_desc':
                $query->orderBy(Account::select('username')->whereColumn('accounts.id', 'tenants.account_id'), 'desc');
                break;
            default: 
                $query->orderBy('name', 'asc');
                break;
        }

        $tenants = $query->get();

        return view('landboard.tenant.index', compact('tenants'));
    }

    public function show($id)
    {
        $tenant = Tenant::with(['account', 'room.landboard'])->findOrFail($id);

        return view('landboard.tenant.show', compact('tenant'));
    }

    public function dashboard()
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        if ($tenant) {
            $tenant->load([
                'room.landboard.account',
                'room.photos',
                'room.rules',
                'room.facilities',
            ]);
        }

        $room = $tenant?->room;
        $landboard = $room?->landboard;

        $payments = $tenant
            ? Payment::where('tenant_id', $tenant->id)->orderBy('due_date', 'asc')->get()
            : collect();

        $activeHistory = $tenant
            ? $tenant->rentalHistories()
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->latest('start_date')
                ->first()
            : null;

        $latestPayment = $tenant
            ? Payment::where('tenant_id', $tenant->id)->latest('due_date')->first()
            : null;

        $latestTransferRequest = $tenant
            ? RoomTransferRequest::where('tenant_id', $tenant->id)->latest()->first()
            : null;

        $histories = $tenant
            ? $tenant->rentalHistories()->latest('start_date')->take(5)->get()
            : collect();

        $daysLeft = null;
        $remaining_time = null;

        if ($activeHistory) {
            $now = Carbon::now();
            $end = Carbon::parse($activeHistory->end_date);

            $daysLeft = $now->diffInDays($end, false);

            if ($now->lt($end)) {
                $diff = $now->diff($end);
                $remaining_time = "{$diff->d} hari {$diff->h} jam";
            } else {
                $remaining_time = 'Masa sewa telah berakhir';
            }
        }

        return view('tenant.dashboard', compact(
            'tenant',
            'landboard',
            'room',
            'payments',
            'activeHistory',
            'latestPayment',
            'latestTransferRequest',
            'histories',
            'daysLeft',
            'remaining_time'
        ));
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
                'password' => Hash::make($request->password),
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

    public function createProfile()
    {
        $account = Auth::user();
        $tenant = $account->tenant;

        if ($tenant->name && $tenant->phone && $tenant->address) {
            return redirect()->route('tenant.dashboard.index')->with('info', 'Profil sudah lengkap.');
        }

        return view('tenant.profile.complete', compact('tenant'));
    }

    public function storeProfile(Request $request)
    {
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        /** @var \App\Models\Tenant $tenant */
        $tenant = $account->tenant;

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'nullable|email|unique:accounts,email,' . $account->id,
            'password'         => ['nullable', 'confirmed', Password::min(6)->letters()->mixedCase()->numbers()],
            'avatar'           => 'nullable|image|max:2048',
            'name'             => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'address'          => 'required|string|max:255',
            'gender'           => 'nullable|in:male,female',
            'activity_type'    => 'nullable|string|max:255',
            'institution_name' => 'nullable|string|max:255',
            'bank_name'        => 'required|string|max:100',
            'bank_account'     => 'required|digits_between:10,16',
            'identity_photo'   => 'nullable|image|max:2048',
            'selfie_photo'     => 'nullable|image|max:2048',
        ]);

        $account->email = $validated['email'] ?? null;
        $account->bank_name = $validated['bank_name'];
        $account->bank_account = $validated['bank_account'];

        if (!empty($validated['password'])) {
            $account->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($account->avatar) {
                Storage::disk('public')->delete($account->avatar);
            }
            $account->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $account->save();

        $tenant->fill([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'gender' => $validated['gender'],
            'activity_type' => $validated['activity_type'],
            'institution_name' => $validated['institution_name'],
        ]);

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

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function editProfile()
    {
        $account = Auth::user();
        $tenant = $account->tenant;

        return view('tenant.profile.edit', compact('account', 'tenant'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        /** @var \App\Models\Tenant $tenant */
        $tenant = $account->tenant;

        $validated = $request->validate([
            'username'         => 'required|string|min:6|max:20|unique:accounts,username,' . $account->id,
            'email'            => 'nullable|email|unique:accounts,email,' . $account->id,
            'password'         => ['nullable', 'confirmed', Password::min(6)->letters()->mixedCase()->numbers()],
            'avatar'           => 'nullable|image|max:2048',
            'name'             => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'address'          => 'required|string|max:255',
            'gender'           => 'nullable|in:male,female',
            'activity_type'    => 'nullable|string|max:255',
            'institution_name' => 'nullable|string|max:255',
            'bank_name'        => 'required|string|max:100',
            'bank_account'     => 'required|digits_between:10,16',
            'identity_photo'   => 'nullable|image|max:2048',
            'selfie_photo'     => 'nullable|image|max:2048',
        ]);

        $account->username = $validated['username'];
        $account->email = $validated['email'] ?? null;
        $account->bank_name = $validated['bank_name'];
        $account->bank_account = $validated['bank_account'];

        if (!empty($validated['password'])) {
            $account->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($account->avatar) {
                Storage::disk('public')->delete($account->avatar);
            }
            $account->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $account->save();

        $tenant->fill([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'gender' => $validated['gender'],
            'activity_type' => $validated['activity_type'],
            'institution_name' => $validated['institution_name'],
        ]);

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

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
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
            $account->password = Hash::make($validated['password']);
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

    public function showPayments()
    {
        $tenant = Auth::user()->tenant;

        $payments = Payment::with('rentalHistory.room')
            ->where('tenant_id', $tenant->id)
            ->orderBy('due_date', 'asc')
            ->get();

        return view('tenant.payment.index', compact('payments'));
    }
}