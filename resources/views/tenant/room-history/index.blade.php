@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Riwayat Sewa Saya</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  @vite('resources/css/app.css')
  <style>
    /* Custom styles for responsive table */
    @media (max-width: 768px) {
      .responsive-table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
      }
      
      .responsive-table table {
        min-width: 100%;
        font-size: 12px;
      }
      
      .responsive-table th,
      .responsive-table td {
        padding: 8px 6px;
        vertical-align: middle;
      }
      
      .responsive-table .status-badge {
        font-size: 10px;
        padding: 2px 6px;
      }
    }
  </style>
</head>
<body class="use-poppins-normal bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-tenant')
    <div id="main-content" class="main-content w-full p-6 md:pt-4">
      <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
        <p><strong class="use-poppins">Riwayat Sewa</strong></p>
        <p class="text-[14px]">Berikut merupakan riwayat sewa anda.</p>
      </div>
      
      <div class="relative max-w-full mx-auto mt-6">
        <div class="bg-white rounded-xl shadow-md p-4 md:p-6">
          @if ($histories->isEmpty())
            <div class="text-center py-16 text-slate-400 text-base">
              Belum ada data sewa yang tercatat.
            </div>
          @else
            <div class="responsive-table overflow-x-auto">
              <table class="min-w-full w-full table-auto text-sm text-slate-700">
                <thead class="bg-slate-100 text-slate-700 uppercase text-xs tracking-wider">
                  <tr>
                    <th class="px-2 md:px-4 py-2 md:py-3 text-left">No. Kamar</th>
                    <th class="px-2 md:px-4 py-2 md:py-3 text-left">Harga / Bulan</th>
                    <th class="px-2 md:px-4 py-2 md:py-3 text-left">Denda</th>
                    <th class="px-2 md:px-4 py-2 md:py-3 text-left">Status Pembayaran</th>
                    <th class="px-2 md:px-4 py-2 md:py-3 text-left">Status Sewa</th>
                    <th class="px-2 md:px-4 py-2 md:py-3 text-left">Tanggal Masuk</th>
                    <th class="px-2 md:px-4 py-2 md:py-3 text-left">Tanggal Keluar</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($histories as $history)
                    @php
                      $payment = $history->payment;
                      $paymentStatus = $payment && $payment->status === 'paid' ? 'Sudah Dibayar' : 'Belum Dibayar';
                      $paymentClass = $payment && $payment->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-700';

                      $statusClass = match($history->computed_status) {
                          'Sedang Berjalan' => 'bg-blue-100 text-blue-800',
                          'Belum Dimulai' => 'bg-yellow-100 text-yellow-800',
                          'Selesai' => 'bg-gray-100 text-gray-800',
                          default => 'bg-red-100 text-red-800',
                      };
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                      <td class="px-2 md:px-4 py-2 md:py-3 border-b border-gray-300">
                        <span class="font-medium">{{ $history->room->room_number }}</span>
                      </td>
                      <td class="px-2 md:px-4 py-2 md:py-3 border-b border-gray-300">
                        <span class="whitespace-nowrap">Rp{{ number_format($history->room->price, 0, ',', '.') }}</span>
                      </td>
                      <td class="px-2 md:px-4 py-2 md:py-3 border-b border-gray-300">
                        <span class="whitespace-nowrap">
                          @if ($payment && $payment->is_penalty)
                            Rp{{ number_format($payment->amount, 0, ',', '.') }}
                          @else
                            Rp0
                          @endif
                        </span>
                      </td>
                      <td class="px-2 md:px-4 py-2 md:py-3 border-b border-gray-300">
                        <span class="status-badge inline-block px-2 py-1 text-xs font-medium rounded {{ $paymentClass }}">
                          {{ $paymentStatus }}
                        </span>
                      </td>
                      <td class="px-2 md:px-4 py-2 md:py-3 border-b border-gray-300">
                        <span class="status-badge inline-block px-2 py-1 text-xs font-medium rounded {{ $statusClass }}">
                          {{ $history->computed_status }}
                        </span>
                      </td>
                      <td class="px-2 md:px-4 py-2 md:py-3 border-b border-gray-300">
                        <span class="whitespace-nowrap">{{ Carbon::parse($history->start_date)->format('d M Y') }}</span>
                      </td>
                      <td class="px-2 md:px-4 py-2 md:py-3 border-b border-gray-300">
                        <span class="whitespace-nowrap">{{ $history->end_date ? Carbon::parse($history->end_date)->format('d M Y') : '—' }}</span>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
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
          sidebar?.classList.add('collapsed');
          sidebar?.classList.remove('mobile-expanded');
          mainContent?.classList.add('collapsed');
          overlay.classList.remove('active');
        } else {
          sidebar?.classList.remove('mobile-expanded');
          overlay.classList.remove('active');
        }
      }

      initializeSidebar();

      if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function () {
          if (window.innerWidth <= 768) {
            const isOpen = sidebar.classList.contains('mobile-expanded');
            sidebar.classList.toggle('mobile-expanded', !isOpen);
            sidebar.classList.toggle('collapsed', isOpen);
            overlay.classList.toggle('active', !isOpen);
          } else {
            sidebar.classList.toggle('collapsed');
            mainContent?.classList.toggle('collapsed');
          }
        });
      }

      overlay.addEventListener('click', function () {
        sidebar?.classList.remove('mobile-expanded');
        sidebar?.classList.add('collapsed');
        overlay.classList.remove('active');
      });

      window.addEventListener('resize', initializeSidebar);

      document.addEventListener('click', function (event) {
        if (!event.target.closest('.search-input-wrapper')) {
          document.querySelectorAll('.filter-sort-dropdown').forEach(d => d.classList.add('hidden'));
        }
      });
    });
  </script>
</body>
</html>