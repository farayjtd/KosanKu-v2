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
  <link rel="stylesheet" href="/style/font.css">
  @vite('resources/css/app.css')
  <style>
    .card-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
    }

    /* New Card Styling */
    .room-card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      margin-bottom: 20px; /* Space between cards */
      display: flex; /* Use flexbox for layout inside the card */
      overflow: hidden; /* Ensure rounded corners on image */
      position: relative;
    }
    .carousel-img {
      display: none;
    }
    .carousel-img.active {
      display: block;
    }

    .room-card .photo-section {
      position: relative;
      width: 35%; /* Adjust as needed for image width */
      aspect-ratio: 16/9; /* Maintain aspect ratio */
      overflow: hidden;
      border-radius: 12px 0 0 12px; /* Rounded corners only on the left side */
    }
    /* Updated for centering and bottom radius */
    .room-card .photo-carousel {
        position: absolute; /* Position the carousel inside photo-section */
        top: 50%;
        left: 0;
        width: 100%;
        height: 100%; /* Take full height of photo-section */
        transform: translateY(-50%); /* Center vertically */
        overflow: hidden;
        border-radius: 0 0 0 12px; /* Add bottom-left radius */
    }

    .room-card .photo-section .carousel-img {
      width: 100%;
      height: 100%;
      object-fit: cover; /* Ensures image covers the area, cropping if necessary */
      display: none;
      /* Remove rounded-lg here, it's now on .photo-carousel */
    }

    .room-card .photo-section .carousel-img.active {
      display: block;
    }

    .room-card .photo-section .carousel-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(0, 0, 0, 0.4);
      color: white;
      border: none;
      padding: 6px 10px;
      cursor: pointer;
      font-size: 16px;
      border-radius: 4px;
    }

    .room-card .photo-section .carousel-btn.prev {
      left: 10px;
    }

    .room-card .photo-section .carousel-btn.next {
      right: 10px;
    }

    .room-card .details-section {
      flex-grow: 1; /* Take remaining space */
      padding: 1.5rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: relative; /* For price positioning */
    }

    .room-card .room-code {
      font-size: 0.85rem;
      color: #777;
      margin-bottom: 0.25rem;
    }

    .room-card .room-name {
      font-size: 1.5rem;
      font-weight: bold;
      color: #333;
      margin-bottom: 0.5rem;
    }

    .room-card .room-price {
      font-family: 'Poppins', sans-serif;
      font-size: 1.8rem;
      font-weight: 700;
      color: #31c594;
      position: absolute;
      top: 1.5rem;
      right: 1.5rem;
    }

    .room-card .room-description { /* This is now for Facilities */
      font-size: 0.95rem;
      color: #555;
      line-height: 1.4;
      margin-bottom: 1rem;
      max-height: 4.2em; /* Approx 3 lines (1.4 * 3) */
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .room-card .facilities-list { /* New style for facilities */
        list-style: none; /* Remove default list bullets */
        padding: 0;
        margin: 0;
        font-size: 0.95rem;
        color: #555;
        line-height: 1.4;
        margin-bottom: 1rem;
        max-height: 4.2em; /* Limit height to approx 3 lines */
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .room-card .facilities-list li::before { /* Custom bullet point for facilities */
        content: "\2022"; /* Unicode for a bullet point */
        color: #31c594; /* Green bullet */
        font-weight: bold;
        display: inline-block;
        width: 1em;
        margin-left: -1em;
    }


    .room-card .product-options {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
      margin-top: 0.5rem;
    }

    .room-card .option-item {
      padding: 0.4rem 0.8rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 0.85rem;
      cursor: pointer;
      transition: all 0.2s;
    }

    .room-card .option-item.selected {
      background-color: #31c594;
      color: white;
      border-color: #31c594;
    }

    .room-card .button-group {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-top: 1.5rem;
    }

    .room-card .action-button {
      background-color: #31c594;
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      font-weight: 600;
      text-decoration: none;
      text-align: center;
      transition: background-color 0.2s ease-in-out;
      flex-grow: 1;
      display: flex; /* Make it a flex container */
      align-items: center; /* Vertically align icon and text */
      justify-content: center; /* Horizontally center icon and text */
      gap: 0.5rem; /* Space between icon and text */
    }

    .room-card .action-button .button-text { /* Hide text on mobile */
      display: inline;
    }
    .room-card .action-button .button-icon { /* Show icon on mobile, hide on desktop */
      display: none;
    }


    .room-card .action-button:hover {
      background-color: #2ba87c;
    }

    .room-card .action-button.danger {
        background-color: #f87171;
    }

    .room-card .action-button.danger:hover {
        background-color: #dc2626;
    }

    .status.available { color: green; font-weight: bold; }
    .status.occupied { color: #dc2626; font-weight: bold; } /* Match image color */
    .status.booked { color: orange; font-weight: bold; }

    .search-input-wrapper {
        position: relative;
    }

    .search-input-wrapper input[type="search"] {
        padding-left: 2.5rem;
        padding-right: 3.5rem;
    }

    .search-input-wrapper .search-icon {
        position: absolute;
        left: 1rem;
    }

    .filter-sort-toggle-btn {
        position: absolute;
        right: 1rem;
    }

    .filter-sort-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        min-width: 200px;
        z-index: 30;
    }

    /* Responsive adjustments for new card layout and buttons */
    @media (max-width: 768px) {
      .room-card {
        flex-direction: column; /* Stack image and details vertically on smaller screens */
      }
      .room-card .photo-section {
        width: 100%; 
        aspect-ratio: 16/9; 
        border-radius: 12px 12px 0 0; 
      }
      .room-card .details-section {
        padding: 1rem; 
      }
      .room-card .room-price {
        position: static; 
        text-align: right; 
        margin-top: 0.5rem; 
        margin-bottom: 0.5rem;
      }
      .room-card .button-group {
        flex-direction: row; 
        justify-content: space-around; 
        padding: 0 0.5rem;
      }
      .room-card .action-button {
        flex-grow: 1;
        flex-basis: 0; 
        padding: 0.75rem 0.5rem;
        font-size: 0.8rem;
      }
      .room-card .action-button .button-text {
        display: none;
      }
      .room-card .action-button .button-icon {
        display: inline;
        font-size: 1.2rem;
      }
    }
  </style>
</head>
<body class="bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">

    {{-- Sidebar --}}
    @include('components.sidebar-landboard')

    {{-- Konten utama --}}
    <div id="main-content" class="main-content p-6 md:pt-4 w-full">
      <div class="search-input-wrapper mb-6 bg-white rounded-xl shadow-md p-3 flex items-center">
        <i class="bi bi-search search-icon text-gray-500"></i>
        <form id="room-search-form" method="GET" class="flex-grow flex items-center relative">
            <input type="search" name="search" placeholder="Cari nomor kamar atau tipe..." value="{{ request('search') }}"
                   class="w-full border-none outline-none bg-transparent">
            <button type="button" class="filter-sort-toggle-btn text-black text-2xl cursor-pointer p-1 rounded-full transition duration-200 ease-in-out hover:bg-gray-100" onclick="toggleFilterSortDropdown(this)">
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
        <p class="text-green-500 mb-3">{{ session('success') }}</p>
      @elseif (session('error'))
        <p class="text-red-500 mb-3">{{ session('error') }}</p>
      @endif

      {{-- Daftar Kamar --}}
      <div class="flex flex-col gap-6">
      @foreach($rooms as $room)
      <div class="flex flex-col sm:flex-row bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Photo section -->
        <div class="relative sm:w-1/3 h-48 sm:h-auto">
          <div class="h-full photo-carousel" data-room-id="{{ $room->id }}">
            @forelse ($room->photos as $index => $photo)
              <img src="{{ asset('storage/'.$photo->path) }}"
                  class="carousel-img {{ $index === 0 ? 'active' : '' }}">
            @empty
              <img src="https://via.placeholder.com/400x250?text=No+Image"
                  class="carousel-img active">
            @endforelse

            @if ($room->photos->count() > 1)
              <button class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 text-white rounded p-1" onclick="prevPhoto('{{ $room->id }}')">&#10094;</button>
              <button class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 text-white rounded p-1" onclick="nextPhoto('{{ $room->id }}')">&#10095;</button>
            @endif
          </div>
        </div>

        <!-- Details section -->
        <div class="flex flex-col flex-1 p-4">
          <div class="flex justify-between leading-tight">
            <h3 class="text-lg md:text-xl font-semibold use-poppins">{{ $room->room_number }} <br> {{ $room->type }}</h3>
            <span class="use-poppins text-xl md:text-2xl font-bold text-emerald-500">Rp{{ number_format($room->price, 0, ',', '.') }}</span>
          </div>

          <div>
            <p class="font-medium mb-1 text-sm md:text-base">Fasilitas:</p>
            <p class="text-xs md:text-sm leading-snug text-gray-700">
              @forelse ($room->facilities as $index => $facility)
                {{ $facility->name }}@if (!$loop->last), @endif
              @empty
                Tidak ada fasilitas yang tercatat.
              @endforelse
            </p>
          </div>

          <div class="flex flex-col text-xs md:text-sm">
            <span class="mt-2"><i class="bi bi-gender-ambiguous mr-2"></i>Gender : {{ ucfirst($room->gender_type) }}</span>
            <span class="mt-1 mb-2"><i class="bi bi-bar-chart-line mr-2"></i>Status :
              <span class="{{ $room->status == 'available' ? 'text-green-600' : ($room->status == 'occupied' ? 'text-red-600' : 'text-orange-500') }}">{{ ucfirst($room->status) }}</span>
            </span>
          </div>

          <div class="mt-auto grid grid-cols-4 gap-2 text-center text-xs">
            <a href="{{ route('landboard.rooms.show', $room->id) }}" class="flex items-center justify-center gap-1 py-2 bg-emerald-500 text-white rounded-md hover:bg-emerald-600"><i class="bi bi-info-circle text-base"></i><span class="hidden md:inline">Detail</span></a>
            <a href="{{ route('landboard.rooms.duplicate-form', $room->id) }}" class="flex items-center justify-center gap-1 py-2 bg-emerald-500 text-white rounded-md hover:bg-emerald-600"><i class="bi bi-files text-base"></i><span class="hidden md:inline">Duplikat</span></a>
            <a href="{{ route('landboard.rooms.edit-form', $room->id) }}" class="flex items-center justify-center gap-1 py-2 bg-emerald-500 text-white rounded-md hover:bg-emerald-600"><i class="bi bi-pencil-square text-base"></i><span class="hidden md:inline">Edit</span></a>
            <form action="{{ route('landboard.rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kamar ini?')" class="contents">
              @csrf
              @method('DELETE')
              <button type="submit" class="flex items-center justify-center gap-1 py-2 bg-red-400 text-white rounded-md hover:bg-red-600 transition"><i class="bi bi-trash text-base"></i><span class="hidden md:inline">Hapus</span></button>
            </form>
          </div>
        </div>
      </div>
      @endforeach
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
  {{-- Carousel Script --}}
  <script>
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