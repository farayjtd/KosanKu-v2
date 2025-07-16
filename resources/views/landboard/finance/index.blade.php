@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Riwayat Keuangan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;500&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="/style/font.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  @vite('resources/css/app.css')
  <style>
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
    @media (max-width: 768px) {
      .financial-table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
      }
    }
  </style>
</head>
<body class="use-poppins-normal bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-landboard')

    <div id="main-content" class="main-content p-4 md:p-6 w-full">
      <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
        <p><strong class="use-poppins">Riwayat Keuangan</strong></p>
        <p class="text-[14px]">Cari data pemasukan dan pengeluaran dengan menuliskan data penghuni.</p>
      </div>
     <div class="text-gray-600 mt-6 search-input-wrapper bg-white rounded-xl shadow-md p-3 flex items-center">
          <i class="bi bi-search search-icon text-gray-500 mr-4"></i>
          <form id="room-search-form" method="GET" class="flex-grow flex items-center relative">
              <input type="text" name="search" placeholder="Cari username"  value="{{ old('search', request('search')) }}"
                    class="pl-8 w-full rounded-4xl border-none outline-none bg-transparent">
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
      <div class="relative mt-6 mb-6">
        <div class="w-full bg-white rounded-xl shadow-md p-6">
          <p class="text-[18px] font-semibold text-gray-700 uppercase">Riwayat Pemasukan</p>
          <div class="p-4">
            @if ($incomePayments->isNotEmpty())
              <div class="overflow-x-auto">
                <table class="financial-table">
                  <thead class="md:table-header-group uppercase">
                    <tr>
                      <th>Tanggal</th>
                      <th>Nama</th>
                      <th>Username</th>
                      <th>Kamar</th>
                      <th>Jumlah</th>
                      <th>Metode</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($incomePayments as $payment)
                      <tr>
                        <td data-label="Tanggal">{{ Carbon::parse($payment->paid_at)->format('d/m/Y') }}</td>
                        <td data-label="Nama">{{ $payment->rentalHistory->tenant->name ?? '[Nama Tidak Ada]' }}</td>
                        <td data-label="Username">{{ $payment->rentalHistory->tenant?->account?->username ?? '[Username Tidak Ada]' }}</td>
                        <td data-label="Kamar">{{ $payment->rentalHistory->room?->room_number ?? '[Kamar Terhapus]' }}</td>
                        <td data-label="Jumlah" class="text-[#31c594] font-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                        <td data-label="Metode">{{ ucfirst($payment->payment_method ?? '-') }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="text-center py-8 text-gray-500">
                <i class="bi bi-inbox text-4xl mb-2"></i>
                @if(request('search'))
                {{-- Desain --}}
                  Tidak ada hasil untuk kata: <strong>"{{ request('search') }}"</strong>
                @else
                  Belum ada pemasukan.
                @endif
              </div>
            @endif
          </div>
        </div>
      </div>

      {{-- Riwayat Pengeluaran --}}
      <div class="relative mb-8">
        <div class="w-full bg-white rounded-xl shadow-md p-6">
          <p class="text-[18px] font-semibold text-gray-700 uppercase">Riwayat Pengeluaran</p>
          <div class="p-4">
            @if ($expensePayments->isNotEmpty())
              <div class="overflow-x-auto mb-4">
                <table class="financial-table">
                  <thead class="md:table-header-group uppercase">
                    <tr>
                      <th>Tanggal</th>
                      <th>Nama</th>
                      <th>Username</th>
                      <th>Kamar</th>
                      <th>Jumlah</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($expensePayments as $payment)
                      <tr>
                        <td data-label="Tanggal">{{ Carbon::parse($payment->paid_at)->format('d/m/Y') }}</td>
                        <td data-label="Nama">{{ $payment->rentalHistory->tenant->name ?? '[Nama Tidak Ada]' }}</td>
                        <td data-label="Username">{{ $payment->rentalHistory->tenant?->account?->username ?? '[Username Tidak Ada]' }}</td>
                        <td data-label="Kamar">{{ $payment->rentalHistory->room?->room_number ?? '[Kamar Terhapus]' }}</td>
                        <td data-label="Jumlah" class="text-red-600 font-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="text-center py-8 text-gray-500">
                <i class="bi bi-inbox text-4xl mb-2"></i>
                @if(request('search'))
                {{-- Desain --}}
                  Tidak ada hasil untuk kata: <strong>"{{ request('search') }}"</strong>
                @else
                  Belum ada Pengeluaran.
                @endif
              </div>
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