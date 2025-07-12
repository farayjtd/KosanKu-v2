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
  @vite('resources/css/app.css')
  <style>
    @media (max-width: 768px) {
      .table-responsive td {
        display: block;
        padding-left: 50%;
        position: relative;
        text-align: left;
        border: none !important;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
      }
      .table-responsive td::before {
        content: attr(data-label);
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        font-weight: 600;
        color: #1e293b;
        font-size: 0.875rem;
      }
      .table-responsive tr {
        display: block;
        margin-bottom: 1rem;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        overflow: hidden;
        padding: 1rem 0;
      }
      .table-responsive thead {
        display: none;
      }
    }
  </style>
</head>
<body class="use-poppins-normal bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-tenant')
    
    <div id="main-content" class="main-content p-6 w-full">
      <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
        <p><strong class="use-poppins">Riwayat Sewa</strong></p>
        <p class="text-[14px]">Berikut merupakan riwayat sewa anda.</p>
      </div>
      <div class="relative max-w-full mx-auto mt-6">
        <div class="bg-white rounded-xl shadow-md p-6">
          @if ($histories->isEmpty())
            <div class="text-center py-16 text-slate-400 text-base">
              Belum ada data sewa yang tercatat.
            </div>
          @else
            <div class="overflow-x-auto">
              <table class="w-full table-auto text-sm text-slate-700 table-responsive">
                <thead class="bg-slate-100 text-slate-700 uppercase text-xs tracking-wider">
                  <tr>
                    <th class="px-4 py-3 text-left">No. Kamar</th>
                    <th class="px-4 py-3 text-left">Harga / Bulan</th>
                    <th class="px-4 py-3 text-left">Denda</th>
                    <th class="px-4 py-3 text-left">Status Pembayaran</th>
                    <th class="px-4 py-3 text-left">Status Sewa</th>
                    <th class="px-4 py-3 text-left">Tanggal Masuk</th>
                    <th class="px-4 py-3 text-left">Tanggal Keluar</th>
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
                      <td data-label="No. Kamar" class="px-4 py-3 border-b border-gray-300">{{ $history->room->room_number }}</td>
                      <td data-label="Harga / Bulan" class="px-4 py-3 border-b border-gray-300">
                        Rp{{ number_format($history->room->price, 0, ',', '.') }}
                      </td>
                      <td data-label="Denda" class="px-4 py-3 border-b border-gray-300">
                        @if ($payment && $payment->is_penalty)
                          Rp{{ number_format($payment->amount, 0, ',', '.') }}
                        @else
                          Rp0
                        @endif
                      </td>
                      <td data-label="Status Pembayaran" class="px-4 py-3 border-b border-gray-300">
                        <span class="inline-block px-2 py-1 text-xs font-medium rounded {{ $paymentClass }}">
                          {{ $paymentStatus }}
                        </span>
                      </td>
                      <td data-label="Status Sewa" class="px-4 py-3 border-b border-gray-300">
                        <span class="inline-block px-2 py-1 text-xs font-medium rounded {{ $statusClass }}">
                          {{ $history->computed_status }}
                        </span>
                      </td>
                      <td data-label="Tanggal Masuk" class="px-4 py-3 border-b border-gray-300">
                        {{ Carbon::parse($history->start_date)->format('d M Y') }}
                      </td>
                      <td data-label="Tanggal Keluar" class="px-4 py-3 border-b border-gray-300">
                        {{ $history->end_date ? Carbon::parse($history->end_date)->format('d M Y') : '—' }}
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
