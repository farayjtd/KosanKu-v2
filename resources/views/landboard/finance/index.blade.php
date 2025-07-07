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
        
  </style>
</head>
<body class="bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    {{-- Sidebar --}}
    @include('components.sidebar-landboard')

    <div id="main-content" class="main-content p-6 md:pt-4 w-full">
        <div class="search-input-wrapper mb-6 bg-white rounded-xl shadow-md p-3 flex items-center">
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
        <div class="w-full bg-white rounded-md shadow-md mb-10">
          <p class="use-poppins text-xl mb-4 p-4 rounded-tr-md rounded-tl-md text-center text-white bg-[#31c594]">Riwayat Pemasukan</p>
          <table class="table-auto w-full bg-white rounded-md shadow-md">
            <thead class="text-black">
              <tr>
                <th class="w-50">Tanggal</th>
                <th class="w-100">Tenant</th>
                <th class="w-auto">Kamar</th>
                <th class="w-auto">Jumlah</th>
                <th class="w-auto">Metode</th>
              </tr>
            </thead>
            <tbody class="text-gray-800 text-center">
              @forelse ($incomePayments as $payment)
                <tr class="border-b-1 border-gray-300">
                  <td>{{ Carbon::parse($payment->paid_at)->format('d M Y') }}</td>
                  <td class="pb-2 text-center">
                      <strong class="text-120px]">{{ $payment->rentalHistory->tenant?->account?->username ?? '[Username Tidak Ada]' }}</strong>
                      ({{ $payment->rentalHistory->tenant?->name ?? 'Belum isi nama' }})
                  </td>
                  <td >{{ $payment->rentalHistory->room?->room_number ?? '[Kamar Terhapus]' }}</td>
                  <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                  <td>{{ ucfirst($payment->payment_method ?? '-') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="p-15">Belum ada pemasukan.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        {{-- Riwayat Pengeluaran --}}
        <div class="w-full bg-white rounded-md shadow-md mb-10">
          <p class="use-poppins text-xl mb-4 p-4 rounded-tr-md rounded-tl-md text-center text-white bg-[#31c594]">Riwayat Pengeluaran</p>
          <table class="table-auto w-full bg-white rounded-md shadow-md">
            <thead class="text-black">
              <tr>
                <th>Tanggal</th>
                <th>Tenant</th>
                <th>Kamar</th>
                <th>Jumlah</th>
              </tr>
            </thead>
            <tbody class="text-gray-800 text-center">
              @forelse ($expensePayments as $payment)
                <tr>
                  <td>{{ Carbon::parse($payment->paid_at)->format('d M Y') }}</td>
                  <td>
                    {{ $payment->rentalHistory->tenant?->name ?? 'Belum isi nama' }}<br>
                    <small>{{ $payment->rentalHistory->tenant?->account?->username ?? '[Username Tidak Ada]' }}</small>
                  </td>
                  <td>{{ $payment->rentalHistory->room?->room_number ?? '[Kamar Terhapus]' }}</td>
                  <td style="color: red;">Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="p-15">Belum ada pengeluaran.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
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
    }</script>
</body>
</html>
