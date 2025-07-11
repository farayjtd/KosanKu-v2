<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Akun Penghuni Kos</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  @vite('resources/css/app.css')
</head>
<body class="use-poppins-normal bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen w-full">
    {{-- Sidebar --}}
    @include('components.sidebar-landboard')

    {{-- Main Content --}}
    <div id="main-content" class="main-content transition-all duration-300 ease-in-out flex-1 md:ml-[240px] p-4 md:p-6">
      <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
        <p><strong class="use-poppins">Buat Akun Penghuni</strong></p>
        <p class="text-[14px]">Buat akun penghuni untuk memudahkan dalam pendataan selanjutnya.</p>
      </div>
      <div class="mt-6 bg-white rounded-2xl shadow-lg overflow-hidden max-w-full mx-auto">
        <!-- <div class="bg-gradient-to-r from-[#31c594] to-[#2ba882] text-white p-6 text-center rounded-t-2xl">
          <h2 class="text-2xl font-bold">
            <i class="bi bi-person-plus mr-2"></i> Buat Akun Penghuni Kos
          </h2>
        </div> -->

        <div class="p-6">
          {{-- Flash Messages --}}
          @if(session('success'))
            <div class="mb-4 px-4 py-2 rounded-lg bg-emerald-100 text-emerald-700 border border-emerald-300">
              <i class="bi bi-check-circle mr-2"></i> {{ session('success') }}
            </div>
          @endif

          @if($errors->any())
            <div class="mb-4 px-4 py-2 rounded-lg bg-red-100 text-red-700 border border-red-300">
              <i class="bi bi-exclamation-triangle mr-2"></i>
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
              <label class="block font-medium text-sm text-gray-700">Username
              </label>
              <input type="text" name="username" value="{{ old('username') }}" required class="text-gray-600 mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20">
            </div>

            <div>
              <label class="block font-medium text-sm text-gray-700">Password</label>
              <div class="relative">
                <input id="password" type="password" name="password" required class="text-gray-600 mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 text-sm focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20">
                <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-[#31c594] transition-colors">
                  <i class="bi bi-eye-slash" id="eyeIcon"></i>
                </button>
              </div>
            </div>

            <div>
              <label class="block font-medium text-sm text-gray-700">Konfirmasi Password</label>
              <div class="relative">
                <input id="confirmpassword" type="password" name="password_confirmation" required class="text-gray-600 mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 pr-10 text-sm focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20">
                <button type="button" id="toggleConfirmPassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-[#31c594] transition-colors">
                  <i class="bi bi-eye-slash" id="eyeIcon-conf"></i>
                </button>
              </div>
            </div>

            <div>
              <label class="block font-medium text-sm text-gray-700">Pilih Kamar
              </label>
              <select name="room_id" required class="text-gray-600 mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20">
                <option value="">-- Pilih Kamar --</option>
                @foreach ($rooms as $room)
                  <option value="{{ $room->id }}">
                    Kamar {{ $room->room_number }} (Rp{{ number_format($room->price, 0, ',', '.') }})
                  </option>
                @endforeach
              </select>
            </div>

            <div>
              <label class="block font-medium text-sm text-gray-700">Durasi Sewa
              </label>
              <select name="duration_months" required class="text-gray-600 mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20">
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
              <label class="block font-medium text-sm text-gray-700">Tanggal Masuk
              </label>
              <input type="date" name="start_date" value="{{ old('start_date') }}" required class="text-gray-600 mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20">
            </div>

            <div class="pt-4">
              <button type="submit" class="w-full bg-[#31c594] text-white px-8 py-3 rounded-lg text-base font-semibold transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-[#31c594]/30">
              <i class="bi bi-save mr-2"></i>Simpan
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
  const togglePassword = document.getElementById("togglePassword");
    const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");

    const passwordInput = document.getElementById("password");
    const confirmpasswordInput = document.getElementById("confirmpassword");

    const eyeIcon = document.getElementById("eyeIcon");
    const eyeIconConf = document.getElementById("eyeIcon-conf");

    togglePassword.addEventListener("click", function () {
      const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);
      eyeIcon.classList.toggle("bi-eye");
      eyeIcon.classList.toggle("bi-eye-slash");
    });

    toggleConfirmPassword.addEventListener("click", function () {
      const type = confirmpasswordInput.getAttribute("type") === "password" ? "text" : "password";
      confirmpasswordInput.setAttribute("type", type);
      eyeIconConf.classList.toggle("bi-eye");
      eyeIconConf.classList.toggle("bi-eye-slash");
    });
  </script>
</body>
</html>