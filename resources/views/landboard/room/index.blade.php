<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Kamar</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  @vite('resources/css/app.css')
  <style>
    .carousel-img {
      display: none;
    }
    .carousel-img.active {
      display: block;
    }

    .status.available { color: green; font-weight: bold; }
    .status.occupied { color: #dc2626; font-weight: bold; }
    .status.booked { color: orange; font-weight: bold; }

    .filter-sort-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        min-width: 200px;
        z-index: 30;
    }
  </style>
</head>
<body class="bg-cover bg-no-repeat bg-center use-poppins-normal" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">

    {{-- Sidebar --}}
    @include('components.sidebar-landboard')

    {{-- Konten utama --}}
    <div id="main-content" class="main-content p-6 md:pt-4 w-full">
      <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-emerald-500 to-emerald-600">
        <p><strong class="use-poppins">Cari Kamar </strong></p>
        <p class="text-sm">Cari kamar untuk memudahkan anda dalam management kamar.</p>
      </div>
      
      <div class="mt-6 mb-6 bg-white rounded-xl shadow-md p-3 flex items-center relative">
        <i class="bi bi-search text-gray-600 absolute left-6"></i>
        <form id="room-search-form" method="GET" class="flex-grow flex items-center relative">
            <input type="text" name="search" placeholder="Cari nomor kamar atau tipe..." value="{{ request('search') }}"
                   class="w-full border-none outline-none bg-transparent pl-12 pr-16">
            <button type="button" class="absolute right-4 text-black text-2xl cursor-pointer p-1 rounded-full transition duration-200 ease-in-out hover:bg-gray-100" onclick="toggleFilterSortDropdown(this)">
                <i class="bi bi-sliders"></i>
            </button>
            <div class="filter-sort-dropdown hidden bg-white rounded-lg shadow-lg mt-2 p-4">
                <div class="mb-4">
                    <label for="status-filter" class="block text-gray-700 font-medium text-sm mb-1">Status:</label>
                    <select id="status-filter" name="status" onchange="document.getElementById('room-search-form').submit()"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        <option value="">Semua</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Booked</option>
                        <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                    </select>
                </div>
                <div>
                    <label for="sort-filter" class="block text-gray-700 font-medium text-sm mb-1">Urutkan:</label>
                    <select id="sort-filter" name="sort" onchange="document.getElementById('room-search-form').submit()"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        <option value="">-- Pilih --</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Termahal</option>
                        <option value="room_az" {{ request('sort') == 'room_az' ? 'selected' : '' }}>No Kamar A-Z</option>
                        <option value="room_za" {{ request('sort') == 'room_za' ? 'selected' : '' }}>No Kamar Z-A</option>
                    </select>
                </div>
            </div>
        </form>
      </div>

      {{-- Flash Message --}}
      @if (session('success'))
        <p class="bg-green-100 border border-green-300 text-green-700 text-sm px-4 py-3 rounded-xl mb-4 break-words">{{ session('success') }}</p>
      @elseif (session('error'))
        <p class="text-red-500 mb-3">{{ session('error') }}</p>
      @endif

      {{-- Daftar Kamar --}}
      <div class="flex flex-col gap-6 mt-6">
      @if ($rooms->isEmpty())
        <div class="text-center text-gray-500 mt-4">
          {{-- Desain --}}
            @if (request('search'))
                Tidak ada kamar yang cocok dengan kata kunci:
                <strong>"{{ request('search') }}"</strong>
            @else
                Data kamar tidak ditemukan.
            @endif
        </div>
      @else
        @foreach($rooms as $room)
        <!-- Card with consistent sizing -->
        <div class="flex flex-col sm:flex-row bg-white rounded-xl shadow-md overflow-hidden h-auto sm:h-64">
          <!-- Photo section with forced 16:9 aspect ratio -->
          <div class="relative w-full sm:w-2/5 h-48 sm:h-full">
            <div class="absolute inset-0 photo-carousel overflow-hidden" data-room-id="{{ $room->id }}">
              @forelse ($room->photos as $index => $photo)
                <img src="{{ asset('storage/'.$photo->path) }}"
                    class="carousel-img {{ $index === 0 ? 'active' : '' }} w-full h-full object-cover">
              @empty
                <img src="/public/assets/room-sample.png" alt="No Photo"
                    class="carousel-img active w-full h-full object-cover">
              @endforelse

              @if ($room->photos->count() > 1)
                <button class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 text-white rounded p-1 hover:bg-black/60 transition-colors" onclick="prevPhoto('{{ $room->id }}')">
                  <i class="bi bi-chevron-left"></i>
                </button>
                <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 text-white rounded p-1 hover:bg-black/60 transition-colors" onclick="nextPhoto('{{ $room->id }}')">
                  <i class="bi bi-chevron-right"></i>
                </button>
              @endif
            </div>
          </div>

          <!-- Details section -->
          <div class="flex flex-col flex-1 p-6 justify-between min-h-0">
            <!-- Header with room info and price -->
            <div class="flex justify-between items-start mb-3">
              <div>
                <h3 class="text-lg md:text-xl font-semibold use-poppins leading-tight">
                  {{ $room->room_number }} <br class="sm:hidden"> 
                  <span class="sm:inline">{{ $room->type }}</span>
                </h3>
              </div>
              <span class="use-poppins text-xl md:text-2xl font-bold text-emerald-500 ml-4">
                Rp{{ number_format($room->price, 0, ',', '.') }}
              </span>
            </div>

            <!-- Facilities -->
            <div class="mb-3">
              <p class="font-medium mb-1 text-sm md:text-base text-gray-800">Fasilitas:</p>
              <p class="text-xs md:text-sm leading-snug text-gray-600 line-clamp-2">
                @forelse ($room->facilities as $index => $facility)
                  {{ $facility->name }}@if (!$loop->last), @endif
                @empty
                  Tidak ada fasilitas yang tercatat.
                @endforelse
              </p>
            </div>

            <!-- Status and Gender -->
            <div class="flex flex-col text-xs md:text-sm mb-4">
              <span class="flex items-center mb-1">
                <i class="bi bi-gender-ambiguous mr-2 text-gray-500"></i>
                <span class="text-gray-700">Gender: {{ ucfirst($room->gender_type) }}</span>
              </span>
              <span class="flex items-center">
                <i class="bi bi-bar-chart-line mr-2 text-gray-500"></i>
                <span class="text-gray-700">Status: </span>
                <span class="ml-1 font-medium {{ $room->status == 'available' ? 'text-green-600' : ($room->status == 'occupied' ? 'text-red-600' : 'text-orange-500') }}">
                  {{ ucfirst($room->status) }}
                </span>
              </span>
            </div>

            <!-- Action buttons -->
            <div class="grid grid-cols-4 gap-3 mt-auto pt-2">
              <a href="{{ route('landboard.rooms.show', $room->id) }}" 
                 class="flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white py-3 px-3 rounded-lg font-semibold transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/30 text-sm">
                <i class="bi bi-info-circle text-base"></i>
                <span class="hidden lg:inline ml-1">Detail</span>
              </a>
              
              <a href="{{ route('landboard.rooms.duplicate-form', $room->id) }}" 
                 class="flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white py-3 px-3 rounded-lg font-semibold transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/30 text-sm">
                <i class="bi bi-files text-base"></i>
                <span class="hidden lg:inline ml-1">Duplikat</span>
              </a>
              
              <a href="{{ route('landboard.rooms.edit-form', $room->id) }}" 
                 class="flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white py-3 px-3 rounded-lg font-semibold transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/30 text-sm">
                <i class="bi bi-pencil-square text-base"></i>
                <span class="hidden lg:inline ml-1">Edit</span>
              </a>
              
              <form action="{{ route('landboard.rooms.destroy', $room->id) }}" method="POST" 
                    onsubmit="return confirm('Yakin ingin menghapus kamar ini?')" class="contents">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="flex items-center justify-center bg-red-500 hover:bg-red-600 text-white py-3 px-3 rounded-lg font-semibold transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-red-500/30 text-sm">
                  <i class="bi bi-trash text-base"></i>
                  <span class="hidden lg:inline ml-1">Hapus</span>
                </button>
              </form>
            </div>
          </div>
        </div>
        @endforeach
      @endif
    </div>
    </div>
  </div>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const toggleBtn = document.getElementById('toggleSidebar');

    const overlay = document.createElement('div');
    overlay.className = 'mobile-overlay fixed inset-0 bg-black bg-opacity-50 z-40 hidden';
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
        overlay.classList.add('hidden');
      } else {
        if (sidebar) {
          sidebar.classList.remove('mobile-expanded');
        }
        overlay.classList.add('hidden');
      }
    }

    initializeSidebar();

    if (toggleBtn && sidebar) {
      toggleBtn.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
          if (sidebar.classList.contains('mobile-expanded')) {
            sidebar.classList.remove('mobile-expanded');
            sidebar.classList.add('collapsed');
            overlay.classList.add('hidden');
          } else {
            sidebar.classList.remove('collapsed');
            sidebar.classList.add('mobile-expanded');
            overlay.classList.remove('hidden');
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
        overlay.classList.add('hidden');
      }
    });

    window.addEventListener('resize', function() {
      initializeSidebar();
    });

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.relative')) {
            const filterSortDropdown = document.querySelector('.filter-sort-dropdown');
            if (filterSortDropdown && !filterSortDropdown.classList.contains('hidden')) {
                filterSortDropdown.classList.add('hidden');
            }
        }
    });
  });

  // Carousel functions
  function nextPhoto(roomId) {
    const container = document.querySelector(`.photo-carousel[data-room-id="${roomId}"]`);
    const images = container.querySelectorAll('.carousel-img');
    let activeIndex = [...images].findIndex(img => img.classList.contains('active'));
    images[activeIndex].classList.remove('active');
    const nextIndex = (activeIndex + 1) % images.length;
    images[nextIndex].classList.add('active');
  }

  function prevPhoto(roomId) {
    const container = document.querySelector(`.photo-carousel[data-room-id="${roomId}"]`);
    const images = container.querySelectorAll('.carousel-img');
    let activeIndex = [...images].findIndex(img => img.classList.contains('active'));
    images[activeIndex].classList.remove('active');
    const prevIndex = (activeIndex - 1 + images.length) % images.length;
    images[prevIndex].classList.add('active');
  }

  function toggleFilterSortDropdown(button) {
      const dropdown = button.nextElementSibling;
      document.querySelectorAll('.filter-sort-dropdown').forEach(d => {
          if (d !== dropdown) {
              d.classList.add('hidden');
          }
      });
      dropdown.classList.toggle('hidden');
      event.stopPropagation();
  }
  </script>
</body>
</html>