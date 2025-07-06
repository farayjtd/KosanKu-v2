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
  <script src="{{ asset('js/sidebar.js') }}" defer></script>
  @vite('resources/css/app.css')

  <style>
    /* CSS untuk Sidebar yang tidak bisa sepenuhnya diganti Tailwind karena media queries bertumpuk atau properti calc() */
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
      min-height: 48px; /* Hanya berlaku untuk profile/logout btn */
    }

    /* Override min-height for collapsed menu-item if needed */
    .sidebar.collapsed .menu-item {
        min-height: unset; /* Reset or define specifically if needed */
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

    /* Hanya sisakan CSS yang tidak bisa langsung di-Tailwind-kan atau properti kompleks */
    .card-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* Adjusted 80px to 1fr for better responsiveness */
      gap: 20px;
    }

    /* Styles untuk Photo Carousel yang melibatkan position absolute/relative dan transform */
    .photo-carousel {
      position: relative;
      width: 100%;
      height: 160px;
      overflow: hidden;
    }

    .carousel-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: none;
    }

    .carousel-img.active {
      display: block;
    }

    .carousel-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(0, 0, 0, 0.4);
      color: white;
      border: none;
      padding: 6px 10px;
      cursor: pointer;
      font-size: 16px;
    }

    .carousel-btn.prev {
      left: 10px;
    }

    .carousel-btn.next {
      right: 10px;
    }

    /* Kebab Menu - Properti yang perlu dipertahankan karena positioning */
    .kebab-menu-container {
      position: absolute;
      top: 12px;
      right: 10px;
      z-index: 10;
    }

    .dropdown-menu {
      position: absolute;
      top: 35px;
      right: 0;
      min-width: 120px;
      overflow: hidden;
      z-index: 20;
    }

    /* Status badges */
    .status.available { color: green; font-weight: bold; }
    .status.occupied { color: red; font-weight: bold; }
    .status.booked { color: orange; font-weight: bold; }
  </style>
</head>
<body class="bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">

    {{-- Sidebar --}}
    @include('components.sidebar-landboard')

    {{-- Konten utama --}}
    <div id="main-content" class="main-content p-6 md:pt-4 w-full">
      <p class="text-xl p-4 rounded-xl text-left bg-[#31c594] text-white use-poppins">Daftar Kamar</p>

      {{-- Flash Message --}}
      @if (session('success'))
        <p class="text-green-500 mb-3">{{ session('success') }}</p>
      @elseif (session('error'))
        <p class="text-red-500 mb-3">{{ session('error') }}</p>
      @endif

      {{-- Filter dan Sort --}}
      <form method="GET" class="flex flex-wrap items-center gap-4 mb-6">
        <div>
          <label class="text-black mr-2">Status:</label>
          <select name="status" onchange="this.form.submit()" class="px-3 py-1 rounded">
            <option value="">Semua</option>
            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
            <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Booked</option>
            <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
          </select>
        </div>

        <div>
          <label class="text-black mr-2">Urutkan:</label>
          <select name="sort" onchange="this.form.submit()" class="px-3 py-1 rounded">
            <option value="">-- Pilih --</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Termahal</option>
            <option value="room_az" {{ request('sort') == 'room_az' ? 'selected' : '' }}>No Kamar A-Z</option>
            <option value="room_za" {{ request('sort') == 'room_za' ? 'selected' : '' }}>No Kamar Z-A</option>
          </select>
        </div>
      </form>

      {{-- Daftar Kamar --}}
      <div class="card-container">
        @foreach ($rooms as $room)
          <div class="card bg-white p-4 rounded-xl shadow-md flex flex-col gap-2 relative">
            {{-- Carousel Foto --}}
            <div class="photo-carousel rounded-lg">
              @forelse ($room->photos as $index => $photo)
                <img src="{{ asset('storage/' . $photo->path) }}"
                     class="carousel-img rounded-lg {{ $index === 0 ? 'active' : '' }}"
                     data-index="{{ $index }}">
              @empty
                <img src="https://via.placeholder.com/240x140?text=No+Image" class="carousel-img active rounded-lg" />
              @endforelse

              @if ($room->photos->count() > 1)
                <button class="carousel-btn prev rounded-md" onclick="prevPhoto({{ $room->id }})">&#10094;</button>
                <button class="carousel-btn next rounded-md" onclick="nextPhoto({{ $room->id }})">&#10095;</button>
              @endif
            </div>

            {{-- Kebab Menu Container --}}
            <div class="kebab-menu-container">
                <button class="kebab-button text-black text-2xl cursor-pointer p-1 rounded-full transition duration-200 ease-in-out hover:bg-gray-100">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div class="dropdown-menu hidden bg-white rounded-lg shadow-lg">
                    <a href="{{ route('landboard.rooms.duplicate-form', $room->id) }}" class="block w-full px-4 py-2 text-left border-none bg-none cursor-pointer text-gray-700 text-sm transition duration-200 ease-in-out hover:bg-gray-100">Duplikat</a>
                    <a href="{{ route('landboard.rooms.show', $room->id) }}" class="block w-full px-4 py-2 text-left border-none bg-none cursor-pointer text-gray-700 text-sm transition duration-200 ease-in-out hover:bg-gray-100">Detail</a>
                    <a href="{{ route('landboard.rooms.edit-form', $room->id) }}" class="block w-full px-4 py-2 text-left border-none bg-none cursor-pointer text-gray-700 text-sm transition duration-200 ease-in-out hover:bg-gray-100">Edit</a>
                    <form action="{{ route('landboard.rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kamar ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn-dropdown block w-full px-4 py-2 text-left border-none bg-none cursor-pointer text-red-500 text-sm transition duration-200 ease-in-out hover:bg-red-50 hover:text-red-700">Hapus</button>
                    </form>
                </div>
            </div>

            {{-- Detail --}}
            <h4 class="text-lg font-semibold">{{ $room->room_number }}</h4>
            <p><strong class="use-poppins">{{ $room->type }}</strong></p>
            <p>Rp{{ number_format($room->price, 0, ',', '.') }}</p>
            <p>Gender: {{ ucfirst($room->gender_type) }}</p>
            <p>Status: <span class="status {{ $room->status }}">{{ ucfirst($room->status) }}</span></p>

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

    // Delegasi event listener untuk kebab buttons
    document.querySelectorAll('.kebab-button').forEach(button => {
        button.addEventListener('click', function(event) {
            toggleKebabMenu(event.currentTarget); // Menggunakan currentTarget untuk memastikan elemen tombol yang diklik
            event.stopPropagation(); // Mencegah event click menyebar ke document dan langsung menutup menu
        });
    });

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.kebab-menu-container')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
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

    function toggleKebabMenu(button) {
        const dropdownMenu = button.nextElementSibling;
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (menu !== dropdownMenu) {
                menu.classList.add('hidden');
            }
        });
        dropdownMenu.classList.toggle('hidden');
    }
  </script>
</body>
</html>