@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pembayaran</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
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

    .success {
      color: #15803d;
      background: #dcfce7;
      padding: 10px 14px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 14px;
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

    .mark-paid-btn {
      padding: 8px 14px;
      background: #31c594;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 13px;
      cursor: pointer;
      transition: background 0.2s ease;
    }

    .mark-paid-btn:hover {
      background: #28a57a;
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
    {{-- Sidebar --}}
    @include('components.sidebar-landboard')

    <div id="main-content" class="main-content p-4 md:p-6 w-full">
      <!-- Search Bar -->
      <div class="search-input-wrapper mb-10 bg-white rounded-xl shadow-md p-3 flex items-center">
        <i class="bi bi-search search-icon text-gray-500 mr-4"></i>
        <form id="payment-search-form" method="GET" class="flex-grow flex items-center relative">
          <input type="text" name="search" placeholder="Cari username, nama tenant, atau kamar" value="{{ request('search') }}"
                class="pl-7 w-full rounded-4xl border-none outline-none bg-transparent">
          <button type="button" class="filter-sort-toggle-btn text-black text-2xl cursor-pointer p-1 rounded-full transition duration-200 ease-in-out hover:bg-gray-100" onclick="toggleFilterSortDropdown(this)">
            <i class="bi bi-sliders"></i>
          </button>
          <div class="filter-sort-dropdown hidden bg-white rounded-lg shadow-lg mt-2 p-4">
            <div>
              <label for="sort-filter" class="block text-gray-700 font-medium text-sm mb-1">Urutkan:</label>
              <select id="sort-filter" name="sort" onchange="document.getElementById('payment-search-form').submit()"
                      class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="due_date_asc" {{ request('sort') == 'due_date_asc' ? 'selected' : '' }}>Jatuh Tempo Terdekat</option>
                <option value="due_date_desc" {{ request('sort') == 'due_date_desc' ? 'selected' : '' }}>Jatuh Tempo Terlama</option>
                <option value="amount_desc" {{ request('sort') == 'amount_desc' ? 'selected' : '' }}>Jumlah Terbesar</option>
                <option value="amount_asc" {{ request('sort') == 'amount_asc' ? 'selected' : '' }}>Jumlah Terkecil</option>
                <option value="username_asc" {{ request('sort') == 'username_asc' ? 'selected' : '' }}>Username A-Z</option>
                <option value="username_desc" {{ request('sort') == 'username_desc' ? 'selected' : '' }}>Username Z-A</option>
              </select>
            </div>
          </div>
        </form>
      </div>

      {{-- Cash Payments --}}
      <div class="relative mb-8">
        <div class="absolute -top-5 left-0 bg-[#31c594] text-white px-6 py-3 rounded-bl-4xl rounded-tr-4xl z-10">
          <h2 class="use-poppins text-base md:text-lg font-semibold">Pembayaran</h2>
        </div>
        <div class="w-full bg-white rounded-2xl shadow-md pt-8">
          <div class="p-4">
            {{-- Pesan sukses --}}
            @if (session('success'))
              <div class="success">{{ session('success') }}</div>
            @endif

            <div class="overflow-x-auto">
              <table class="financial-table">
                <thead class="hidden md:table-header-group uppercase text-gray-700 text-xs tracking-wider">
                  <tr>
                    <th>Username</th>
                    <th>Tenant Name</th>
                    <th>Room</th>
                    <th>Due Date</th>
                    <th>Amount</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($payments as $payment)
                    <tr>
                      <td data-label="Username">{{ $payment->rentalHistory->tenant->account->username }}</td>
                      <td data-label="Tenant Name">{{ $payment->rentalHistory->tenant->name ?: 'Belum diisi' }}</td>
                      <td data-label="Room">{{ $payment->rentalHistory->room->room_number }}</td>
                      <td data-label="Due Date">{{ Carbon::parse($payment->due_date)->format('d M Y') }}</td>
                      <td data-label="Amount" class="text-[#31c594] font-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                      <td data-label="Action">
                        <form action="{{ route('landboard.payments.markPaid', $payment->id) }}" method="POST">
                          @csrf
                          <button type="submit" class="mark-paid-btn">Mark as Paid</button>
                        </form>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500">
                          @if (request('search'))
                            {{-- Desain --}}
                            <p class="text-sm">
                              Tidak ada hasil untuk kata: <strong>"{{ request('search') }}"</strong>
                            </p>
                          @else
                            <div class="flex flex-col items-center">
                              <i class="bi bi-inbox text-4xl mb-2"></i>
                              <p class="text-sm">No pending cash payments.</p>
                            </div>
                          @endif
                        </td>
                      </tr>
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