<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Aktivasi Ulang Tenant - KosanKu</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  @vite('resources/css/app.css')
</head>
<body class="use-poppins-normal bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  
  <div id="wrapper" class="flex min-h-screen w-full">
    @include('components.sidebar-landboard')

    <div id="main-content" class="main-content p-6 md:pt-4 w-full">
      <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
        <p><strong class="use-poppins">Aktifkan Kembali</strong></p>
        <p class="text-[14px]">Disini anda dapat kembali mengaktifkan tenant dengan username: {{ $tenant->account->username }}</p>
      </div>
        <div class="mt-6 bg-white p-6 rounded-3xl shadow-gray-100 shadow-xs">
          @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 text-sm px-4 py-3 rounded-xl mb-4 break-words">
              {{ session('success') }}
            </div>
          @endif

          @if($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-700 text-sm px-4 py-3 rounded-xl mb-4 break-words">
              <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('landboard.tenants.reactivate', $tenant->id) }}" method="POST">
            @csrf

            <label for="room_id" class="block text-sm font-medium text-gray-700 mt-2">
              <i class="bi bi-door-open mr-2"></i>Pilih Kamar
            </label>
            <select 
              name="room_id" 
              id="room_id" 
              required 
              class="text-gray-600 w-full mt-1 px-4 py-2 rounded-xl text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0"
            >
              <option value="">-- Pilih Kamar --</option>
              @foreach ($rooms as $room)
                <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                  Kamar {{ $room->room_number }} (Rp{{ number_format($room->price, 0, ',', '.') }})
                </option>
              @endforeach
            </select>

            <label for="duration_months" class="block text-sm font-medium text-gray-700 mt-4">
              <i class="bi bi-calendar-range mr-2"></i>Durasi Sewa
            </label>
            <select 
              name="duration_months" 
              id="duration_months" 
              required 
              class="text-gray-600 w-full mt-1 px-4 py-2 rounded-xl text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0"
            >
              <option value="0.1" {{ old('duration_months') == 0.1 ? 'selected' : '' }}>5 hari (uji coba)</option>
              @foreach ([1, 3, 6, 12] as $bulan)
                <option value="{{ $bulan }}" {{ old('duration_months') == $bulan ? 'selected' : '' }}>
                  {{ $bulan }} bulan
                </option>
              @endforeach
            </select>

            <label for="start_date" class="block text-sm font-medium text-gray-700 mt-4">
              <i class="bi bi-calendar-date mr-2"></i>Tanggal Masuk
            </label>
            <input 
              type="date" 
              name="start_date" 
              id="start_date" 
              value="{{ old('start_date') }}" 
              required 
              class="text-gray-600 w-full mt-1 px-4 py-2 rounded-xl text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0"
            >

            <button 
              type="submit" 
              class="w-60 mt-6 py-2 bg-[#31c594] text-white px-8 rounded-lg text-base font-semibold transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-[#31c594]/30"
            >
              Aktifkan Kembali
            </button>
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
  });
</script>
</body>
</html>