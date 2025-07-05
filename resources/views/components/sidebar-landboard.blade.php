<div class="fixed top-4 left-4 h-[95vh] w-[230px] bg-white rounded-3xl shadow-xl flex flex-col justify-between px-6 py-4 z-50 overflow-hidden">
  @php
    use Illuminate\Support\Str;
    $user = auth()->user();
    $currentRoute = Route::currentRouteName();
    $linkClasses = 'flex items-center gap-3 px-4 py-2 rounded-lg text-sm font-medium transition hover:bg-emerald-100 hover:text-emerald-600';
  @endphp

  {{-- Navigasi --}}
  <nav class="flex-1 w-full">
    <ul class="space-y-2">
      <li>
        <a href="{{ route('landboard.dashboard.index') }}"
           class="{{ $linkClasses }} {{ $currentRoute === 'landboard.dashboard.index' ? 'bg-emerald-100 text-emerald-600 font-semibold' : 'text-slate-700' }}">
           <i class="bi bi-house-door-fill"></i> Dashboard
        </a>
      </li>

      <li>
        <a href="{{ route('landboard.rooms.create-form') }}"
           class="{{ $linkClasses }} {{ $currentRoute === 'landboard.rooms.create-form' ? 'bg-emerald-100 text-emerald-600 font-semibold' : 'text-slate-700' }}">
           <i class="bi bi-plus-square"></i> Buat Kamar
        </a>
      </li>

      <li>
        <a href="{{ route('landboard.rooms.index') }}"
           class="{{ $linkClasses }} {{ Str::startsWith($currentRoute, 'landboard.rooms.') && $currentRoute !== 'landboard.rooms.create-form' ? 'bg-emerald-100 text-emerald-600 font-semibold' : 'text-slate-700' }}">
           <i class="bi bi-door-open"></i> Data Kamar
        </a>
      </li>

      <li>
        <a href="{{ route('landboard.room-transfer.index') }}"
           class="{{ $linkClasses }} {{ $currentRoute === 'landboard.room-transfer.index' ? 'bg-emerald-100 text-emerald-600 font-semibold' : 'text-slate-700' }}">
           <i class="bi bi-arrow-left-right"></i> Pindah Kamar
        </a>
      </li>

      <li>
        <a href="{{ route('landboard.tenants.create-form') }}"
           class="{{ $linkClasses }} {{ $currentRoute === 'landboard.tenants.create-form' ? 'bg-emerald-100 text-emerald-600 font-semibold' : 'text-slate-700' }}">
           <i class="bi bi-person-plus-fill"></i> Tambah Penghuni
        </a>
      </li>

      <li>
        <a href="{{ route('landboard.tenants.index') }}"
           class="{{ $linkClasses }} {{ Str::startsWith($currentRoute, 'landboard.tenants.') && $currentRoute !== 'landboard.tenants.create-form' ? 'bg-emerald-100 text-emerald-600 font-semibold' : 'text-slate-700' }}">
           <i class="bi bi-people-fill"></i> Data Penghuni
        </a>
      </li>

      <li>
        <a href="{{ route('landboard.penalty.settings') }}"
           class="{{ $linkClasses }} {{ $currentRoute === 'landboard.penalty.settings' ? 'bg-emerald-100 text-emerald-600 font-semibold' : 'text-slate-700' }}">
           <i class="bi bi-cash-coin"></i> Pengaturan Penalti
        </a>
      </li>

      <li>
        <a href="{{ route('landboard.finance.index') }}"
           class="{{ $linkClasses }} {{ $currentRoute === 'landboard.finance.index' ? 'bg-emerald-100 text-emerald-600 font-semibold' : 'text-slate-700' }}">
           <i class="bi bi-bar-chart-line-fill"></i> Riwayat Keuangan
        </a>
      </li>

      <li>
        <a href="{{ route('landboard.rental.history.landboard') }}"
           class="{{ $linkClasses }} {{ $currentRoute === 'landboard.rental.history.landboard' ? 'bg-emerald-100 text-emerald-600 font-semibold' : 'text-slate-700' }}">
           <i class="bi bi-journal-text"></i> Riwayat Sewa
        </a>
      </li>

      <li>
        <a href="{{ route('landboard.payments.index') }}"
           class="{{ $linkClasses }} {{ $currentRoute === 'landboard.payments.index' ? 'bg-emerald-100 text-emerald-600 font-semibold' : 'text-slate-700' }}">
           <i class="bi bi-cash-stack"></i> Pembayaran
        </a>
      </li>
    </ul>
  </nav>

  {{-- Tombol Profil --}}
  <div class="pt-6 w-full">
    <form action="{{ route('landboard.profile.update-form') }}" method="GET" class="m-0">
      @csrf
      <button type="submit"
              class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-[#31c594] text-white font-semibold rounded-lg transition hover:bg-emerald-700 hover:scale-105">
        <i class="bi bi-person-gear"></i> Profil
      </button>
    </form>
  </div>

  {{-- Logout --}}
  <div class="pt-6 w-full">
    <form action="{{ route('logout') }}" method="POST" class="m-0">
      @csrf
      <button type="submit"
              class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-500 text-white font-semibold rounded-lg transition hover:bg-red-600 hover:scale-105">
        <i class="bi bi-box-arrow-right"></i> Logout
      </button>
    </form>
  </div>
</div>
