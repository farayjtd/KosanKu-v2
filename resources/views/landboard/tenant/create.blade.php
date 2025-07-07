<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Akun Tenant</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  @vite('resources/css/app.css')
  <style>[x-cloak]{display:none}
    .sidebar.collapsed {
      width: 80px;
    }

    .sidebar.collapsed .menu-text,
    .sidebar.collapsed .menu-title,
    .sidebar.collapsed .group-title,
    .sidebar.collapsed .logo-text,
    .sidebar.collapsed .profile-btn .btn-text,
    .sidebar.collapsed .logout-btn .btn-text {
      display: none;
    }

    .sidebar.collapsed .menu-item,
    .sidebar.collapsed .profile-btn,
    .sidebar.collapsed .logout-btn {
      justify-content: center;
      padding: 0.45rem;
      min-height: 48px;
    }
    .sidebar.collapsed .menu-item {
        min-height: unset;
    }

    .sidebar-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      padding: 1rem;
    }

    .sidebar-footer {
      margin-top: auto;
      padding: 1rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

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
  <div id="wrapper" class="flex min-h-screen w-full">
    {{-- Sidebar --}}
    @include('components.sidebar-landboard')

    {{-- Main Content --}}
    <div id="main-content" class="main-content transition-all duration-300 ease-in-out flex-1 md:ml-[240px] p-4 md:p-6">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden max-w-xl mx-auto">
        <div class="bg-gradient-to-r from-[#31c594] to-[#2ba882] text-white p-6 text-center rounded-t-2xl">
          <h2 class="text-2xl font-bold">
            <i class="bi bi-person-plus mr-2"></i> Buat Akun Tenant
          </h2>
        </div>

        <div class="p-6">
          {{-- Flash Messages --}}
          @if(session('success'))
            <div class="mb-4 px-4 py-2 rounded-lg bg-emerald-100 text-emerald-700 border border-emerald-300">
              <i class="bi bi-check-circle-fill mr-2"></i> {{ session('success') }}
            </div>
          @endif

          @if($errors->any())
            <div class="mb-4 px-4 py-2 rounded-lg bg-red-100 text-red-700 border border-red-300">
              <i class="bi bi-exclamation-triangle-fill mr-2"></i>
              <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('landboard.tenants.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
              <label class="block font-medium text-sm text-gray-700">
                <i class="bi bi-person-fill mr-1"></i> Username
              </label>
              <input type="text" name="username" value="{{ old('username') }}" required class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20">
            </div>

            <div>
              <label class="block font-medium text-sm text-gray-700">
                <i class="bi bi-lock-fill mr-1"></i> Password
              </label>
              <input type="password" name="password" required class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20">
            </div>

            <div>
              <label class="block font-medium text-sm text-gray-700">
                <i class="bi bi-lock-fill mr-1"></i> Konfirmasi Password
              </label>
              <input type="password" name="password_confirmation" required class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20">
            </div>

            <div>
              <label class="block font-medium text-sm text-gray-700">
                <i class="bi bi-house-door-fill mr-1"></i> Pilih Kamar
              </label>
              <select name="room_id" required class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20">
                <option value="">-- Pilih Kamar --</option>
                @foreach ($rooms as $room)
                  <option value="{{ $room->id }}">
                    Kamar {{ $room->room_number }} (Rp{{ number_format($room->price, 0, ',', '.') }})
                  </option>
                @endforeach
              </select>
            </div>

            <div>
              <label class="block font-medium text-sm text-gray-700">
                <i class="bi bi-clock-history mr-1"></i> Durasi Sewa
              </label>
              <select name="duration_months" required class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20">
                <option value="">-- Pilih Durasi --</option>
                <option value="0.1" {{ old('duration_months') == 0.1 ? 'selected' : '' }}>5 hari (uji coba)</option>
                @foreach ([1, 3, 6, 12] as $bulan)
                  <option value="{{ $bulan }}" {{ old('duration_months') == $bulan ? 'selected' : '' }}>
                    {{ $bulan }} bulan
                  </option>
                @endforeach
              </select>
            </div>

            <div>
              <label class="block font-medium text-sm text-gray-700">
                <i class="bi bi-calendar-event mr-1"></i> Tanggal Masuk
              </label>
              <input type="date" name="start_date" value="{{ old('start_date') }}" required class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20">
            </div>

            <div class="pt-4">
              <button type="submit" class="w-full bg-[#31c594] text-white px-8 py-3 rounded-lg text-base font-semibold transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-[#31c594]/30">
                <i class="bi bi-plus-circle mr-1"></i> Buat Akun
              </button>
            </div>
          </form>
      </div>
    </div>
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

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.search-input-wrapper')) {
            const filterSortDropdown = document.querySelector('.filter-sort-dropdown');
            if (filterSortDropdown && !filterSortDropdown.classList.contains('hidden')) {
                filterSortDropdown.classList.add('hidden');
            }
        }
    });
  });
  </script>
</body>
</html>