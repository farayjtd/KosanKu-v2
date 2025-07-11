@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perpanjangan Sewa</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  @vite('resources/css/app.css')
</head>
<body class="use-poppins-normal bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-tenant')
    <div id="main-content" class="main-content p-6 md:pt-4 w-full">
      <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
        <p><strong class="use-poppins">Perpanjangan Sewa</strong></p>
        <p class="text-[14px]">Anda dapat mengajukan perpanjangan sewa lebih awal.</p>
      </div>
      <div class="relative mt-6 max-w-full mx-auto">
        <div class="bg-white rounded-xl shadow p-6 space-y-6">
          @php
            $room = $history->room ?? null;
            $landboard = $room->landboard ?? null;
            $startDate = Carbon::parse($history->start_date ?? now());
            $endDate = Carbon::parse($history->end_date ?? now());
            $decisionDays = $landboard->decision_days_before_end ?? 5;
            $limitDate = (clone $endDate)->subDays($decisionDays);
            $canRenew = $can_renew ?? false;
            $paymentStatus = $history->payment->status ?? null;
            $now = now();
            $remainingTime = $now->lt($endDate)
              ? $now->diff($endDate)->format('%d hari %h jam')
              : 'Masa sewa telah berakhir';
          @endphp
          <div class="text-sm text-slate-700 space-y-1">
            <p><span class="font-semibold">Kamar:</span> {{ $room->room_number ?? '-' }}</p>
            <p><span class="font-semibold">Tanggal Sewa:</span> {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}</p>
            <p><span class="font-semibold">Perpanjangan Dibuka:</span> {{ $limitDate->format('d M Y') }} ({{ $decisionDays }} hari sebelum berakhir)</p>
          </div>
          @if (session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded-md text-sm">
              {{ session('error') }}
            </div>
          @endif

          @if ($errors->any())
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded-md text-sm">
              <strong>Terjadi kesalahan:</strong>
              <ul class="list-disc list-inside mt-1">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          @if ($paymentStatus !== 'paid')
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded-md text-sm">
              Anda harus menyelesaikan pembayaran sewa saat ini terlebih dahulu sebelum mengajukan perpanjangan.
            </div>
          @elseif (!$canRenew)
            <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-md text-sm">
              Sisa {{ $remainingTime }}, sewa berakhir pada {{ $endDate->format('d M Y') }}.
            </div>
          @else
            <form action="{{ route('tenant.renewal.store', $history->id) }}" method="POST" class="space-y-4">
              @csrf

              <div>
                <label for="duration" class="block font-medium text-slate-700 mb-1">Durasi Perpanjangan</label>
                <select id="duration" name="duration" required
                        class="w-full rounded-md border-gray-300 focus:ring focus:ring-blue-200 px-4 py-2 text-sm">
                  <option value="">-- Pilih Durasi --</option>
                  <option value="0.1" {{ old('duration') == 0.1 ? 'selected' : '' }}>5 Hari (uji coba)</option>
                  <option value="1" {{ old('duration') == 1 ? 'selected' : '' }}>1 Bulan</option>
                  <option value="3" {{ old('duration') == 3 ? 'selected' : '' }}>3 Bulan</option>
                  <option value="6" {{ old('duration') == 6 ? 'selected' : '' }}>6 Bulan</option>
                  <option value="12" {{ old('duration') == 12 ? 'selected' : '' }}>12 Bulan</option>
                </select>
              </div>

              <button type="submit"
                      class="flex items-center justify-center bg-green-50 hover:bg-green-100 text-green-700 p-4 rounded-lg transition-all duration-200 hover:-translate-y-1 hover:shadow-md">
                Ajukan Perpanjangan
              </button>
            </form>
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
  </script>
</body>
</html>
