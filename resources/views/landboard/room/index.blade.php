<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Kamar</title>

  {{-- Tambahan CSS sidebar --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  <script src="{{ asset('js/sidebar.js') }}" defer></script>
  @vite('resources/css/app.css')

  <style>
    .sidebar.collapsed {
            width: 80px;
        }
        
        .sidebar.collapsed .menu-text {
            display: none;
        }
        
        .sidebar.collapsed .menu-title {
            display: none;
        }
        
        .sidebar.collapsed .group-title {
            display: none;
        }
        
        .sidebar.collapsed .logo-text {
            display: none;
        }
        
        .sidebar.collapsed .menu-item {
            justify-content: center;
            padding: 0.45rem;
        }
        
        .sidebar.collapsed .profile-btn,
        .sidebar.collapsed .logout-btn {
            padding: 0.45rem;
            justify-content: center;
            min-height: 48px;
        }
        
        .sidebar.collapsed .profile-btn .btn-text,
        .sidebar.collapsed .logout-btn .btn-text {
            display: none;
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
            
            .sidebar .menu-text {
                display: none;
            }
            
            .sidebar .menu-title {
                display: none;
            }
            
            .sidebar .group-title {
                display: none;
            }
            
            .sidebar .logo-text {
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
            
            .sidebar .profile-btn .btn-text,
            .sidebar .logout-btn .btn-text {
                display: none;
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
              
              /* Overlay untuk mobile */
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
    .card-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
    }

    .card {
      background: white;
      padding: 1rem;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .photo-carousel {
      position: relative;
      width: 100%;
      height: 160px;
      overflow: hidden;
      border-radius: 10px;
    }

    .carousel-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: none;
      border-radius: 10px;
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
      border-radius: 4px;
      font-size: 16px;
    }

    .carousel-btn.prev {
      left: 10px;
    }

    .carousel-btn.next {
      right: 10px;
    }

    .button {
      display: inline-block;
      padding: 6px 12px;
      background-color: #31c594;
      color: white;
      text-align: center;
      border-radius: 6px;
      text-decoration: none;
      font-size: 14px;
      margin-top: 4px;
    }

    .button:hover {
      background-color: #2ba87c;
    }

    .delete-btn {
      background-color: #f87171;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 6px;
      margin-top: 4px;
      font-size: 14px;
      cursor: pointer;
    }

    .delete-btn:hover {
      background-color: #dc2626;
    }

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
      <h2 class="text-2xl font-bold text-white mb-4">Daftar Kamar</h2>

      {{-- Flash Message --}}
      @if (session('success'))
        <p class="text-green-500">{{ session('success') }}</p>
      @elseif (session('error'))
        <p class="text-red-500">{{ session('error') }}</p>
      @endif

      {{-- Filter dan Sort --}}
      <form method="GET" class="flex flex-wrap items-center gap-4 mb-6">
        <div>
          <label class="text-white mr-2">Status:</label>
          <select name="status" onchange="this.form.submit()" class="px-3 py-1 rounded">
            <option value="">Semua</option>
            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
            <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Booked</option>
            <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
          </select>
        </div>

        <div>
          <label class="text-white mr-2">Urutkan:</label>
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
          <div class="card">
            {{-- Carousel Foto --}}
            <div class="photo-carousel" data-room-id="{{ $room->id }}">
              @forelse ($room->photos as $index => $photo)
                <img src="{{ asset('storage/' . $photo->path) }}"
                     class="carousel-img {{ $index === 0 ? 'active' : '' }}"
                     data-index="{{ $index }}">
              @empty
                <img src="https://via.placeholder.com/240x140?text=No+Image" class="carousel-img active" />
              @endforelse

              @if ($room->photos->count() > 1)
                <button class="carousel-btn prev" onclick="prevPhoto({{ $room->id }})">&#10094;</button>
                <button class="carousel-btn next" onclick="nextPhoto({{ $room->id }})">&#10095;</button>
              @endif
            </div>

            {{-- Detail --}}
            <h4 class="text-lg font-semibold">{{ $room->room_number }}</h4>
            <p><strong>{{ $room->type }}</strong></p>
            <p>Rp{{ number_format($room->price, 0, ',', '.') }}</p>
            <p>Gender: {{ ucfirst($room->gender_type) }}</p>
            <p>Status: <span class="status {{ $room->status }}">{{ ucfirst($room->status) }}</span></p>

            {{-- Tombol Aksi --}}
            <a href="{{ route('landboard.rooms.duplicate-form', $room->id) }}" class="button">Duplikat</a>
            <a href="{{ route('landboard.rooms.show', $room->id) }}" class="button">Detail</a>
            <a href="{{ route('landboard.rooms.edit-form', $room->id) }}" class="button">Edit</a>

            <form action="{{ route('landboard.rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kamar ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="delete-btn">Hapus</button>
            </form>
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
  </script>
</body>
</html>
