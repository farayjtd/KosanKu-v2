<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pengaturan Penalti</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  @vite('resources/css/app.css')
  <style>
    .main-content {
      margin-left: 240px;
      width: calc(100% - 240px);
      transition: all 0.3s ease;
      min-height: 100vh;
    }

    .main-content.collapsed {
      margin-left: 80px;
      width: calc(100% - 80px);
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 80px;
      }
      .sidebar .menu-text,
      .sidebar .menu-title,
      .sidebar .group-title,
      .sidebar .logo-text,
      .sidebar .profile-btn .btn-text,
      .sidebar .logout-btn .btn-text {
        display: none;
      }
      .sidebar .menu-item {
        justify-content: center;
        padding: 0.75rem;
      }
      .sidebar .profile-btn,
      .sidebar .logout-btn {
        padding: 0.75rem;
        justify-content: center;
        min-height: 48px;
      }
      .main-content {
        margin-left: 80px;
        width: calc(100% - 80px);
      }
      .main-content.collapsed {
        margin-left: 80px;
        width: calc(100% - 80px);
      }
    }

    @media (max-width: 640px) {
      .sidebar {
        width: 60px;
      }
      .sidebar .menu-item {
        padding: 0.5rem;
      }
      .sidebar .profile-btn,
      .sidebar .logout-btn {
        padding: 0.5rem;
        min-height: 40px;
      }
      .main-content {
        margin-left: 60px;
        width: calc(100% - 60px);
      }
      .main-content.collapsed {
        margin-left: 60px;
        width: calc(100% - 60px);
      }
      @media (max-width: 768px) {
        .sidebar.mobile-expanded {
          width: 100vw !important;
          z-index: 60;
        }
        .sidebar.mobile-expanded .menu-text,
        .sidebar.mobile-expanded .menu-title,
        .sidebar.mobile-expanded .group-title,
        .sidebar.mobile-expanded .logo-text,
        .sidebar.mobile-expanded .btn-text {
          display: block !important;
        }
        .sidebar.mobile-expanded .menu-item {
          justify-content: flex-start !important;
          padding: 0.5rem 1rem !important;
        }
        .sidebar.mobile-expanded .profile-btn,
        .sidebar.mobile-expanded .logout-btn {
          justify-content: center !important;
          padding: 0.5rem 1rem !important;
        }
        .mobile-overlay {
          display: none;
          position: fixed;
          top: 0;
          left: 0;
          width: 100vw;
          height: 100vh;
          background: rgba(0, 0, 0, 0.5);
          z-index: 50;
        }
        .mobile-overlay.active {
          display: block;
        }
      }
    }
  </style>
</head>
<body class="bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    {{-- Sidebar --}}
    @include('components.sidebar-landboard')

    <main id="main-content" class="main-content flex-1 p-4 md:p-6 md:ml-[240px] transition-all duration-300 ease-in-out">
      <div class="mx-auto max-w-xl overflow-hidden rounded-2xl shadow-lg bg-white">
        {{-- Header --}}
        <div class="rounded-t-2xl bg-gradient-to-r from-[#31c594] to-[#2ba882] p-6 text-center text-white">
          <h2 class="text-2xl font-bold"><i class="bi bi-sliders mr-2"></i>Pengaturan Penalti</h2>
        </div>

        <div class="p-6 bg-white">
          {{-- Flash --}}
          @if(session('success'))
            <div class="mb-4 flex items-start gap-2 rounded-lg border border-emerald-300 bg-emerald-100 px-4 py-2 text-sm text-emerald-700">
              <i class="bi bi-check-circle-fill pt-0.5"></i><span>{{ session('success') }}</span>
            </div>
          @endif

          @if($errors->any())
            <div class="mb-4 flex items-start gap-2 rounded-lg border border-red-300 bg-red-100 px-4 py-2 text-sm text-red-700">
              <i class="bi bi-exclamation-triangle-fill pt-0.5"></i>
              <ul class="list-disc pl-4">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('landboard.penalty.update') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Aktifkan penalti --}}
            <label class="flex items-center gap-3 font-semibold text-gray-800">
              <input type="checkbox" id="penaltyEnabled" name="is_penalty_enabled" {{ $landboard->is_penalty_enabled ? 'checked' : '' }} class="h-5 w-5 rounded accent-[#8d735b]">
              Aktifkan Penalti
            </label>

            {{-- Denda telat --}}
            <div>
              <label class="mb-1 block text-sm font-semibold text-gray-800">Jumlah Denda Telat Bayar (Rp)</label>
              <input type="number" name="late_fee_amount" value="{{ old('late_fee_amount', $landboard->late_fee_amount) }}" class="penalty-field w-full rounded-lg border border-[#cfc4b5] bg-[#fdfdfb] px-3 py-2 text-sm focus:border-[#31c594] focus:outline-none focus:ring-2 focus:ring-[#31c594]/20">
            </div>

            <div>
              <label class="mb-1 block text-sm font-semibold text-gray-800">Jumlah Hari Setelah Jatuh Tempo Sebelum Denda (hari)</label>
              <input type="number" name="late_fee_days" value="{{ old('late_fee_days', $landboard->late_fee_days) }}" class="penalty-field w-full rounded-lg border border-[#cfc4b5] bg-[#fdfdfb] px-3 py-2 text-sm focus:border-[#31c594] focus:outline-none focus:ring-2 focus:ring-[#31c594]/20">
            </div>

            {{-- Penalti keluar --}}
            <label class="flex items-center gap-3 font-semibold text-gray-800">
              <input type="checkbox" id="moveoutEnabled" name="is_penalty_on_moveout" {{ $landboard->is_penalty_on_moveout ? 'checked' : '' }} class="h-5 w-5 rounded accent-[#8d735b]">
              Penalti Jika Keluar Sebelum Masa Sewa Habis
            </label>

            <div>
              <label class="mb-1 block text-sm font-semibold text-gray-800">Nominal Penalti Keluar Sebelum Waktu (Rp)</label>
              <input type="number" name="moveout_penalty_amount" value="{{ old('moveout_penalty_amount', $landboard->moveout_penalty_amount) }}" class="moveout-field w-full rounded-lg border border-[#cfc4b5] bg-[#fdfdfb] px-3 py-2 text-sm focus:border-[#31c594] focus:outline-none focus:ring-2 focus:ring-[#31c594]/20">
            </div>

            {{-- Penalti pindah kamar --}}
            <label class="flex items-center gap-3 font-semibold text-gray-800">
              <input type="checkbox" id="roomChangeEnabled" name="is_penalty_on_room_change" {{ $landboard->is_penalty_on_room_change ? 'checked' : '' }} class="h-5 w-5 rounded accent-[#8d735b]">
              Penalti Jika Pindah Kamar
            </label>

            <div>
              <label class="mb-1 block text-sm font-semibold text-gray-800">Nominal Penalti Pindah Kamar (Rp)</label>
              <input type="number" name="room_change_penalty_amount" value="{{ old('room_change_penalty_amount', $landboard->room_change_penalty_amount) }}" class="roomchange-field w-full rounded-lg border border-[#cfc4b5] bg-[#fdfdfb] px-3 py-2 text-sm focus:border-[#31c594] focus:outline-none focus:ring-2 focus:ring-[#31c594]/20">
            </div>

            {{-- Perpanjangan Sewa --}}
            <div>
            <label for="decision_days_before_end" class="mb-1 block text-sm font-semibold text-gray-800">
                Tampilkan Tombol Perpanjangan Sewa <span id="days-preview">{{ old('decision_days_before_end', $landboard->decision_days_before_end) }}</span> Hari Sebelum Habis
              </label>
              <input type="number" name="decision_days_before_end" id="decision_days_before_end" value="{{ old('decision_days_before_end', $landboard->decision_days_before_end) }}" class="penalty-field w-full rounded-lg border border-[#cfc4b5] bg-[#fdfdfb] px-3 py-2 text-sm focus:border-[#31c594] focus:outline-none focus:ring-2 focus:ring-[#31c594]/20" oninput="document.getElementById('days-preview').innerText = this.value">
            </div>

            <button type="submit" class="w-full rounded-lg bg-[#31c594] px-8 py-3 text-base font-semibold text-white transition duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-[#31c594]/30">
              <i class="bi bi-save mr-2"></i>Simpan Pengaturan
            </button>
          </form>
        </div>
      </div>
    </main>
  </div>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const mainContent = document.getElementById('main-content');
  const toggleBtn = document.getElementById('toggleSidebar');
  
  const overlay = document.createElement('div');
  overlay.className = 'mobile-overlay';
  overlay.id = 'mobile-overlay';
  document.body.appendChild(overlay);

  function initializeSidebar() {
    if (window.innerWidth <= 768) {
      if (sidebar) {
        sidebar.classList.add('collapsed');
        sidebar.classList.remove('mobile-expanded');
      }
      if (mainContent) {
        mainContent.classList.add('collapsed');
      }
      overlay.classList.remove('active');
    } else {
      if (sidebar) {
        sidebar.classList.remove('mobile-expanded');
      }
      overlay.classList.remove('active');
    }
  }

  initializeSidebar();

  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener('click', function() {
      if (window.innerWidth <= 768) {
        if (sidebar.classList.contains('mobile-expanded')) {
          sidebar.classList.remove('mobile-expanded');
          sidebar.classList.add('collapsed');
          overlay.classList.remove('active');
        } else {
          sidebar.classList.remove('collapsed');
          sidebar.classList.add('mobile-expanded');
          overlay.classList.add('active');
        }
      } else {
        sidebar.classList.toggle('collapsed');
        if (mainContent) {
          mainContent.classList.toggle('collapsed');
        }
      }
    });
  }
  
  overlay.addEventListener('click', function() {
    if (window.innerWidth <= 768) {
      sidebar.classList.remove('mobile-expanded');
      sidebar.classList.add('collapsed');
      overlay.classList.remove('active');
    }
  });

  window.addEventListener('resize', function() {
    initializeSidebar();
  });

      // === enable / disable inputs ===
      const bindToggle = (checkboxId, fieldSelector) => {
        const cb = document.getElementById(checkboxId);
        const fields = document.querySelectorAll(fieldSelector);
        const setState = () => {
          fields.forEach(f => {
            f.disabled = !cb.checked;
            f.classList.toggle('opacity-50', !cb.checked);
            f.classList.toggle('cursor-not-allowed', !cb.checked);
          });
        };
        cb.addEventListener('change', setState);
        setState(); // initial
      };

      // Master toggle controls all .penalty-field
      bindToggle('penaltyEnabled', '.penalty-field');
      // Individual toggles
      bindToggle('moveoutEnabled', '.moveout-field');
      bindToggle('roomChangeEnabled', '.roomchange-field');
    });
  </script>
</body>
</html>
