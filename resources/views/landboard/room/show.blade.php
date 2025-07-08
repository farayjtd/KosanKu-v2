<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Detail Kamar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
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
      .photo-carousel {
      position: relative;
      overflow: hidden;
      border-radius: 12px;
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
      background: rgba(0, 0, 0, 0.5);
      color: white;
      border: none;
      padding: 8px 12px;
      cursor: pointer;
      border-radius: 4px;
      transition: background 0.3s ease;
    }

    .carousel-btn:hover {
      background: rgba(0, 0, 0, 0.7);
    }

    .carousel-btn.prev {
      left: 10px;
    }

    .carousel-btn.next {
      right: 10px;
    }

    .photo-indicators {
      position: absolute;
      bottom: 10px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 6px;
    }

    .indicator {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.5);
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .indicator.active {
      background: white;
    }
  </style>
</head>
<body class="bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-landboard')
    <div id="main-content" class="main-content p-6 md:pt-4 w-full">
      <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-[#31c594] to-[#2ba882] text-white p-6 text-center">
          <h2 class="text-xl font-semibold">
            <i class="bi bi-info-circle mr-2"></i>Detail Kamar
          </h2>
        </div>

        <div class="p-6 space-y-6">
          <div>
            <p><strong>Tipe:</strong> {{ $room->type }}</p>
            <P><strong>Nomor:</strong> {{ $room->room_number }} </p>
            <p><strong>Harga:</strong> Rp{{ number_format($room->price, 0, ',', '.') }}</p>
            <p><strong>Gender:</strong> {{ ucfirst($room->gender_type) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($room->status) }}</p>
          </div>

          <div>
            <h3 class="text-lg font-semibold mb-2">Foto Kamar</h3>
            @if ($room->photos->isEmpty())
              <p class="text-gray-500">Tidak ada foto tersedia.</p>
            @else
              <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach ($room->photos as $photo)
                  <img src="{{ asset('storage/' . $photo->path) }}" alt="Foto Kamar" class="w-full h-40 object-cover rounded-lg">
                @endforeach
              </div>
            @endif
          </div>

          <div>
            <h3 class="text-lg font-semibold mb-2">Fasilitas</h3>
            @if ($room->facilities->isEmpty())
              <p class="text-gray-500">Tidak ada fasilitas ditambahkan.</p>
            @else
              <ul class="list-disc list-inside text-gray-700">
                @foreach ($room->facilities as $facility)
                  <li>{{ $facility->name }}</li>
                @endforeach
              </ul>
            @endif
          </div>

          <div>
            <h3 class="text-lg font-semibold mb-2">Aturan</h3>
            @if ($room->rules->isEmpty())
              <p class="text-gray-500">Tidak ada aturan ditentukan.</p>
            @else
              <ul class="list-disc list-inside text-gray-700">
                @foreach ($room->rules as $rule)
                  <li>{{ $rule->name }}</li>
                @endforeach
              </ul>
            @endif
          </div>
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
