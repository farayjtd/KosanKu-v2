@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Riwayat Sewa Tenant</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  @vite('resources/css/app.css')
  <style>
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

    .use-poppins {
      font-family: 'Poppins', sans-serif;
    }

    /* Responsive table styles */
    .financial-table {
      width: 100%;
      border-collapse: collapse;
    }

    .financial-table th,
    .financial-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #e5e7eb;
    }

    .financial-table th {
      background-color: #f9fafb;
      font-weight: 600;
      color: #374151;
      font-size: 14px;
    }

    .financial-table td {
      font-size: 14px;
      color: #6b7280;
    }

    .financial-table tr:hover {
      background-color: #f9fafb;
    }

    /* Status badges */
    .status-badge {
      padding: 4px 8px;
      border-radius: 6px;
      font-weight: 500;
      display: inline-block;
      font-size: 12px;
    }

    .status-ongoing {
      background-color: #d1fae5;
      color: #065f46;
    }

    .status-finished {
      background-color: #fee2e2;
      color: #991b1b;
    }

    .status-upcoming {
      background-color: #fef9c3;
      color: #92400e;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
      .financial-table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
      }

      .financial-table thead,
      .financial-table tbody,
      .financial-table th,
      .financial-table td,
      .financial-table tr {
        display: block;
      }

      .financial-table thead tr {
        position: absolute;
        top: -9999px;
        left: -9999px;
      }

      .financial-table tr {
        border: 1px solid #e5e7eb;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 8px;
        background-color: white;
      }

      .financial-table td {
        border: none;
        position: relative;
        padding: 6px 6px 6px 50%;
        text-align: left;
      }

      .financial-table td:before {
        content: attr(data-label);
        position: absolute;
        left: 6px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
        font-weight: 600;
        color: #374151;
      }
    }
  </style>
</head>
<body class="bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    {{-- Sidebar --}}d
    @include('components.sidebar-landboard')

    <div id="main-content" class="main-content p-4 md:p-6 w-full">
      <!-- Search Bar -->
      <div class="search-input-wrapper mb-10 bg-white rounded-xl shadow-md p-3 flex items-center">
        <i class="bi bi-search search-icon text-gray-500 mr-4"></i>
        <form id="tenant-search-form" method="GET" class="flex-grow flex items-center relative">
          <input type="search" name="search" placeholder="Cari nama tenant atau kamar" value="{{ request('search') }}"
                class="w-full rounded-4xl border-none outline-none bg-transparent">
          <button type="button" class="filter-sort-toggle-btn text-black text-2xl cursor-pointer p-1 rounded-full transition duration-200 ease-in-out hover:bg-gray-100" onclick="toggleFilterSortDropdown(this)">
            <i class="bi bi-sliders"></i>
          </button>
          <div class="filter-sort-dropdown hidden bg-white rounded-lg shadow-lg mt-2 p-4">
            <div>
              <label for="sort-filter" class="block text-gray-700 font-medium text-sm mb-1">Urutkan:</label>
              <select id="sort-filter" name="sort" onchange="document.getElementById('tenant-search-form').submit()"
                      class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="start_desc" {{ request('sort') == 'start_desc' ? 'selected' : '' }}>Terbaru</option>
                <option value="start_asc" {{ request('sort') == 'start_asc' ? 'selected' : '' }}>Terlama</option>
                <option value="tenant_asc" {{ request('sort') == 'tenant_asc' ? 'selected' : '' }}>Tenant A-Z</option>
                <option value="tenant_desc" {{ request('sort') == 'tenant_desc' ? 'selected' : '' }}>Tenant Z-A</option>
                <option value="room_asc" {{ request('sort') == 'room_asc' ? 'selected' : '' }}>Kamar A-Z</option>
                <option value="room_desc" {{ request('sort') == 'room_desc' ? 'selected' : '' }}>Kamar Z-A</option>
              </select>
            </div>
          </div>
        </form>
      </div>

      {{-- Riwayat Sewa --}}
      <div class="relative mb-8">
        <div class="absolute -top-5 left-0 bg-[#31c594] text-white px-6 py-3 rounded-bl-4xl rounded-tr-4xl z-10">
          <h2 class="use-poppins text-base md:text-lg font-semibold">Riwayat Sewa Penghuni</h2>
        </div>
        <div class="w-full bg-white rounded-2xl shadow-md pt-8">
          <div class="p-4">
            <div class="overflow-x-auto">
              <table class="financial-table">
                <thead class="hidden md:table-header-group uppercase text-gray-700 text-xs tracking-wider">
                  <tr>
                    <th>Nama Tenant</th>
                    <th>Nama Kamar</th>
                    <th>Mulai</th>
                    <th>Berakhir</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($histories as $history)
                    <tr>
                      <td data-label="Nama Tenant">{{ $history->tenant?->name ?? '[Tenant dihapus]' }}</td>
                      <td data-label="Nama Kamar">{{ $history->room?->room_number ?? '[Kamar dihapus]' }}</td>
                      <td data-label="Mulai">{{ Carbon::parse($history->start_date)->format('d M Y') }}</td>
                      <td data-label="Berakhir">{{ $history->end_date ? Carbon::parse($history->end_date)->format('d M Y') : '—' }}</td>
                      <td data-label="Status">
                        <span class="status-badge {{
                          $history->computed_status === 'Sedang Berjalan' ? 'status-ongoing' :
                          ($history->computed_status === 'Selesai' ? 'status-finished' : 'status-upcoming')
                        }}">
                          {{ $history->computed_status }}
                        </span>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center py-8 text-gray-500">
                        <div class="flex flex-col items-center">
                          <i class="bi bi-inbox text-4xl mb-2"></i>
                          @if(request('search'))
                          {{-- Desain --}}
                            Tidak ada hasil untuk kata: <strong>"{{ request('search') }}"</strong>
                          @else
                            Belum ada riwayat sewa ditemukan.
                          @endif
                        </div>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
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