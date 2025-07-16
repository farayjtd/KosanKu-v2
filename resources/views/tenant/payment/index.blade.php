<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tagihan Pembayaran</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  @vite('resources/css/app.css')
</head>
<body class="use-poppins-normal bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-tenant')

    <div id="main-content" class="main-content p-6 md:pt-4 w-full">
      <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
        <p><strong class="use-poppins">Tagihan Pembayaran</strong></p>
        <p class="text-[14px]">Berikut merupakan tagihan pembayaran anda.</p>
      </div>

      <div class="mt-6 relative max-w-screen mx-auto bg-white rounded-xl shadow p-6">
        @if($payments->isEmpty())
          <div class="text-center py-16 text-slate-400 text-base">
            Belum ada tagihan untuk saat ini.
          </div>
        @else
          <div class="overflow-x-auto">
            <table class="w-full table-auto text-sm text-slate-700">
              <thead class="bg-slate-100 text-slate-700 uppercase text-xs tracking-wider">
                <tr>
                  <th class="px-4 py-3 text-left">Kamar</th>
                  <th class="px-4 py-3 text-left">Jumlah</th>
                  <th class="px-4 py-3 text-left">Jatuh Tempo</th>
                  <th class="px-4 py-3 text-left">Status</th>
                  <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($payments as $payment)
                  @php
                    $isPaid = $payment->status === 'paid';
                    $badgeClass = $isPaid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-700';
                    $badgeText = $isPaid ? 'Sudah Dibayar' : 'Belum Dibayar';
                  @endphp
                  <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-3 border-b border-gray-300">{{ $payment->rentalHistory->room->room_number ?? '-' }}</td>
                    <td class="px-4 py-3 border-b border-gray-300">Rp{{ number_format($payment->total_amount ?? $payment->amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 border-b border-gray-300">{{ \Carbon\Carbon::parse($payment->due_date)->format('d M Y') }}</td>
                    <td class="px-4 py-3 border-b border-gray-300">
                      <span class="inline-block px-2 py-1 text-xs font-medium rounded {{ $badgeClass }}">
                        {{ $badgeText }}
                      </span>
                    </td>
                    <td class="px-4 py-3 border-b border-gray-300">
                      @if(!$isPaid)
                        <button class="bg-[#31c594] text-white px-3 py-1 rounded hover:bg-[#2ab88d] text-xs"
                          onclick="openModal({{ $payment->rental_history_id }})">
                          Bayar
                        </button>
                      @else
                        <span class="text-slate-400 italic text-sm">-</span>
                      @endif
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

  <!-- Modal -->
  <div id="payment-modal" class="fixed inset-0 z-50 hidden items-center justify-center flex">
    <div class="absolute inset-0 bg-gray-300 opacity-70"></div>
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
      <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-lg">
        <i class="bi bi-x-lg"></i>
      </button>
      <form id="modal-form" method="POST" action="#" class="space-y-4">
        @csrf
        <input type="hidden" name="rental_id" id="modal-rental-id">
        <h2 class="text-lg font-semibold text-gray-600">Pilih Metode Pembayaran</h2>
        <div class="space-y-2 max-h-64 overflow-y-auto pr-2">
          @foreach($channels as $channel)
            <label class="flex items-center space-x-2 text-sm text-gray-600">
              <input type="radio" name="payment_method" value="{{ $channel['code'] }}" required>
              <span>{{ $channel['name'] }} ({{ $channel['group'] }})</span>
            </label>
          @endforeach
        </div>
        <button type="submit" class="px-4 py-1 bg-[#31c594] text-white text-md font-semibold rounded-xl mx-auto block hover:bg-[#2ab88d] duration-300 cursor-pointer">
          Lanjutkan Pembayaran
        </button>
      </form>
    </div>
  </div>

  <script>
    function toggleMethodForm(id) {
      document.querySelectorAll('.method-form').forEach(form => form.classList.add('hidden'));
      const target = document.getElementById('method-form-' + id);
      if (target) target.classList.remove('hidden');
    }
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
    function toggleMethodForm(id) {
  // Sembunyikan semua form
  document.querySelectorAll('.method-form').forEach(form => {
    if (!form.classList.contains('hidden')) {
      form.classList.add('hidden');
    }
  });

  // Tampilkan form yang sesuai
  const target = document.getElementById('method-form-' + id);
  if (target) {
    target.classList.toggle('hidden');
  }
}
function openModal(rentalId) {
  const modal = document.getElementById('payment-modal');
  modal.classList.remove('hidden');

  const form = document.getElementById('modal-form');
  const hiddenInput = document.getElementById('modal-rental-id');

  // Isi form data
  hiddenInput.value = rentalId;

  // Update action
  form.action = `{{ url('tenant/payment') }}/${rentalId}/create-invoice`;
}

  function closeModal() {
    document.getElementById('payment-modal').classList.add('hidden');
  }

  </script>
</body>
</html>
