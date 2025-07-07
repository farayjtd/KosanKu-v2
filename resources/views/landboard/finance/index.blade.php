@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Riwayat Keuangan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  <script src="{{ asset('js/sidebar.js') }}" defer></script>
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
        
  </style>
</head>
<body class="bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    {{-- Sidebar --}}
    @include('components.sidebar-landboard')

    <div id="main-content" class="main-content p-4 md:p-6 w-full">
        <!-- Search Bar -->
        <div class="search-input-wrapper mb-10 bg-white rounded-xl shadow-md p-3 flex items-center">
          <i class="bi bi-search search-icon text-gray-500 mr-4"></i>
          <form id="room-search-form" method="GET" class="flex-grow flex items-center relative">
              <input type="search" name="search" placeholder="Cari username" value="{{ request('username') }}"
                    class="w-full rounded-4xl border-none outline-none bg-transparent">
              <button type="button" class="filter-sort-toggle-btn text-black text-2xl cursor-pointer p-1 rounded-full transition duration-200 ease-in-out hover:bg-gray-100" onclick="toggleFilterSortDropdown(this)">
                  <i class="bi bi-sliders"></i>
              </button>
              <div class="filter-sort-dropdown hidden bg-white rounded-lg shadow-lg mt-2 p-4">
                  <div>
                      <label for="sort-filter" class="block text-gray-700 font-medium text-sm mb-1">Urutkan:</label>
                      <select id="sort-filter" name="sort" onchange="document.getElementById('room-search-form').submit()"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                          <option value="">-- Pilih --</option>
                          <option value="amount_desc" {{ request('sort') == 'amount_desc' ? 'selected' : '' }}>Jumlah Bayar Terbesar</option>
                          <option value="amount_asc" {{ request('sort') == 'amount_asc' ? 'selected' : '' }}>Jumlah Bayar Terkecil</option>
                          <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Pembayaran Terbaru</option>
                          <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Pembayaran Terlama</option>
                      </select>
                  </div>
              </div>
          </form>
        </div>

        {{-- Riwayat Pemasukan --}}
        <div class="relative mb-8">
          <div class="absolute -top-5 left-0 bg-[#31c594] text-white px-6 py-3 rounded-bl-4xl rounded-tr-4xl z-10">
            <h2 class="use-poppins text-base md:text-lg font-semibold">Riwayat Pemasukan</h2>
          </div>
          <div class="w-full bg-white rounded-lg shadow-md pt-4">
            <div class="p-4">
            @forelse ($incomePayments as $payment)
            <h3 class="mt-4 text-[16px] font-semibold text-gray-800 text-sm">{{ $payment->rentalHistory->tenant?->name ?? 'Anonymous' }}</h3>
            <div class="bg-white p-4 border-b-1 border-gray-300">
                <div class="grid grid-cols-1 md:grid-cols-1 gap-2 text-xs">
                  <div class="flex justify-between text-[14px]">
                    <span class="text-gray-600">Tanggal:</span>
                    <span class="text-gray-600 text-right">{{ Carbon::parse($payment->paid_at)->format('d/m/Y') }}</span>
                  </div>
                  
                  <div class="flex justify-between text-[14px]">
                    <span class="text-gray-600">Tenant:</span>
                    <span class="text-right">{{ $payment->rentalHistory->tenant?->account?->username ?? '[Username Tidak Ada]' }}</span>
                  </div>
                  
                  <div class="flex justify-between text-[14px]">
                    <span class="text-gray-600">Kamar:</span>
                    <span class="text-gray-600 text-right">{{ $payment->rentalHistory->room?->room_number ?? '[Kamar Terhapus]' }}</span>
                  </div>
                  
                  <div class="flex justify-between text-[14px]">
                    <span class="text-gray-600">Jumlah:</span>
                    <span class="text-right text-[#31c594]">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                  </div>
                  
                  <div class="flex justify-between text-[14px]">
                    <span class="text-gray-600">Metode:</span>
                    <span class="text-gray-600 text-right">{{ ucfirst($payment->payment_method ?? '-') }}</span>
                  </div>
                </div>
              </div>
            @empty
              <div class="text-center py-8 text-gray-500">
                <i class="bi bi-inbox text-4xl mb-2"></i>
                <p class="text-sm">Belum ada pemasukan.</p>
              </div>
            @endforelse
          </div>
        </div>

        {{-- Riwayat Pengeluaran --}}
        <div class="relative mb-8 mt-10">
          <div class="absolute -top-5 left-0 bg-[#31c594] text-white px-6 py-3 rounded-bl-4xl rounded-tr-4xl z-10">
            <h2 class="use-poppins text-base md:text-lg font-semibold">Riwayat Pengeluaran</h2>
          </div>
          <div class="w-full bg-white rounded-lg shadow-md pt-12">
            <div class="p-4 space-y-3">
            @forelse ($expensePayments as $payment)
              <div class="bg-white border border-l-4 border-l-red-500 border-gray-200 rounded-lg p-4">
                <div class="flex justify-between items-start mb-2">
                  <h3 class="font-semibold text-gray-800 text-sm">{{ $payment->rentalHistory->tenant?->name ?? 'Belum isi nama' }}</h3>
                  <span class="text-xs text-gray-500">{{ Carbon::parse($payment->paid_at)->format('d/m/Y') }}</span>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-xs">
                  <div>
                    <span class="text-gray-600">Tanggal:</span>
                    <div class="font-medium">{{ Carbon::parse($payment->paid_at)->format('d/m/Y') }}</div>
                  </div>
                  
                  <div>
                    <span class="text-gray-600">Tenant:</span>
                    <div class="font-medium">{{ $payment->rentalHistory->tenant?->account?->username ?? '[Username Tidak Ada]' }}</div>
                  </div>
                  
                  <div>
                    <span class="text-gray-600">Kamar:</span>
                    <div class="font-medium">{{ $payment->rentalHistory->room?->room_number ?? '[Kamar Terhapus]' }}</div>
                  </div>
                  
                  <div>
                    <span class="text-gray-600">Jumlah:</span>
                    <div class="font-medium text-red-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                  </div>
                </div>
              </div>
            @empty
              <div class="text-center py-8 text-gray-500">
                <i class="bi bi-inbox text-4xl mb-2"></i>
                <p class="text-sm">Belum ada pengeluaran.</p>
              </div>
            @endforelse
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