<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Detail Kamar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;500&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  @vite('resources/css/app.css')
</head>

<body class="use-poppins-normal bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-landboard')

    <div id="main-content" class="main-content p-6 md:pt-4 flex-1">
      <!-- Header Section -->
      <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
        <p><strong class="use-poppins">Detail Kamar</strong></p>
        <p class="text-[14px]">Berikut merupakan detail dari kamar <strong>{{ $room->room_number }}</strong>.</p>
      </div>

      <div class="p-6 bg-white rounded-xl mt-4">
        <!-- Room Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-4">
          <h4 class="text-lg font-semibold text-gray-700">Informasi Kamar</h4>
          <div class="mt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="border-b border-gray-100 pb-2">
                <span class="text-gray-600 font-medium text-sm">Tipe:</span>
                <span class="text-gray-700 font-semibold text-sm ml-2">{{ $room->type }}</span>
              </div>

              <div class="border-b border-gray-100 pb-2">
                <span class="text-gray-600 font-medium text-sm">Nomor:</span>
                <span class="text-gray-700 font-semibold text-sm ml-2">{{ $room->room_number }}</span>
              </div>

              <div class="border-b border-gray-100 pb-2">
                <span class="text-gray-600 font-medium text-sm">Harga:</span>
                <span class="text-gray-700 font-semibold text-sm ml-2">Rp{{ number_format($room->price, 0, ',', '.') }}</span>
              </div>

              <div class="border-b border-gray-100 pb-2">
                <span class="text-gray-600 font-medium text-sm">Gender:</span>
                <span class="text-gray-700 font-semibold text-sm ml-2">{{ ucfirst($room->gender_type) }}</span>
              </div>

              <div class="border-b border-gray-100 pb-2 md:col-span-2">
                <span class="text-gray-600 font-medium text-sm">Status:</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2
                  {{ $room->status == 'available' ? 'bg-green-100 text-green-800' : 
                     ($room->status == 'occupied' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                  {{ ucfirst($room->status) }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Room Photos -->
        <div class="mt-6 p-4 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <h4 class="text-lg font-semibold text-gray-700">Foto Kamar</h4>
          <div class="mt-4">
            @if ($room->photos->isEmpty())
              <p class="text-gray-500 text-sm">Tidak ada foto tersedia.</p>
            @else
              <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($room->photos as $photo)
                  <div class="aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                    <img 
                      src="{{ asset('storage/' . $photo->path) }}" 
                      alt="Foto Kamar" 
                      class="w-full h-full object-cover hover:scale-105 transition-transform duration-200"
                    >
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>

        <!-- Room Facilities -->
        <div class="mt-6 p-4 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <h4 class="text-lg font-semibold text-gray-700">Fasilitas</h4>
          <div class="mt-4">
            @if ($room->facilities->isEmpty())
              <p class="text-gray-500 text-sm">Tidak ada fasilitas ditambahkan.</p>
            @else
              <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                @foreach ($room->facilities as $facility)
                  <div class="flex items-center text-sm text-gray-700">
                    <i class="bi bi-check-circle-fill text-green-500 mr-2"></i>
                    {{ $facility->name }}
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>

        <!-- Room Rules -->
        <div class="mt-6 p-4 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <h4 class="text-lg font-semibold text-gray-700">Aturan</h4>
          <div class="mt-4">
            @if ($room->rules->isEmpty())
              <p class="text-gray-500 text-sm">Tidak ada aturan ditentukan.</p>
            @else
              <div class="space-y-2">
                @foreach ($room->rules as $rule)
                  <div class="flex items-center text-sm text-gray-700">
                    <i class="bi bi-circle-fill text-xs mr-2"></i>
                    {{ $rule->name }}
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>

        <!-- Back Button -->
        <div class="flex justify-start mt-6">
          <a 
            href="{{ route('landboard.rooms.index') }}" 
            class="bg-[#31c594] text-white px-8 py-4 rounded-lg text-base font-semibold transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-[#31c594]/30"
          >
            <i class="bi bi-arrow-left mr-2"></i>
            Kembali
          </a>
        </div>
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