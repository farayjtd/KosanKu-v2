<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Duplikat Kamar</title>
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
  </style>
</head>
<body class="bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">

  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-landboard')

    <div id="main-content" class="main-content p-6 md:pt-4 w-full">
      <div class="text-center max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
        <h2 class="use-poppins">Duplikat Kamar Tipe: {{ $room->type }}</h2>

        <div class="bg-gray-100 p-4 rounded-lg mb-4">
          <p>Duplikasi akan menyalin semua data kamar seperti <strong>fasilitas, aturan, harga, gender, dan foto</strong>.</p>
          <p>
            Nomor terakhir: <strong>{{ $lastNumber }}</strong><br>
            Kamar baru akan dimulai dari: <strong>{{ $room->type }}-{{ $lastNumber + 1 }}</strong>
          </p>
        </div>

        <form method="POST" action="{{ route('landboard.rooms.duplicate', $room->id) }}" class="space-y-4 mt-4">
          @csrf

          <div>
            <label for="room_quantity" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Kamar yang Akan Dibuat:</label>
            <input type="number" name="room_quantity" id="room_quantity" min="1" required
                  class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition duration-150" />
          </div>

          <button type="submit"
                  class="bg-emerald-500 text-white px-6 py-2 rounded-md font-semibold hover:bg-emerald-600 transition duration-200">
            Duplikat Sekarang
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
