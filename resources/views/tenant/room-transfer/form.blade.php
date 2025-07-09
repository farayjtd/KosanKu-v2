@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pindah Kamar</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  @vite('resources/css/app.css')
</head>
<body class="bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-tenant')
    <div id="main-content" class="main-content p-6 md:pt-4">
      <div class="relative mt-6">
          <div class="bg-white rounded-xl shadow p-6 max-w-2xl mx-auto space-y-6 relative">
            <div class="absolute -top-4 left-0 bg-[#31c594] text-white px-6 py-2 rounded-bl-3xl rounded-tr-3xl shadow">
              <h2 class="use-poppins text-sm md:text-base font-semibold">Pindah Kamar</h2>
            </div>

            {{-- Flash Messages --}}
            @if (session('success'))
              <div class="bg-green-100 text-green-800 px-4 py-2 rounded-md">{{ session('success') }}</div>
            @endif
            @if (session('error'))
              <div class="bg-red-100 text-red-800 px-4 py-2 rounded-md">{{ session('error') }}</div>
            @endif

            {{-- Informasi Kamar Saat Ini --}}
            <div class="text-sm space-y-2 mt-6">
              <p class="text-gray-500">
                Setelah pindah, masa sewa kamar baru akan dimulai selama 1 bulan. Anda bisa memperpanjang kembali sesuai kebutuhan.
              </p>
              <p><span class="font-semibold">Kamar Saat Ini:</span> {{ $currentHistory->room->room_number }}</p>
              <p><span class="font-semibold">Sisa Hari:</span> {{ $daysLeft }} hari</p>
              <p><span class="font-semibold">Denda Pindah Kamar:</span> Rp{{ number_format($currentHistory->room->landboard->room_change_penalty_amount ?? 0, 0, ',', '.') }}</p>
              <p><span class="font-semibold">Estimasi Refund Manual:</span> Rp{{ number_format($refundAmount, 0, ',', '.') }}</p>
            </div>

            {{-- Status Permintaan Terakhir --}}
            @isset($latestRequest)
              <div class="bg-gray-50 border border-gray-200 rounded-md p-4 space-y-1 text-sm">
                <p>
                  <span class="font-semibold">Status Permintaan Sebelumnya:</span>
                  @if ($latestRequest->status === 'pending')
                    <span class="text-yellow-600 font-medium">Menunggu Persetujuan</span>
                  @elseif ($latestRequest->status === 'approved')
                    <span class="text-green-600 font-medium">Disetujui</span>
                  @elseif ($latestRequest->status === 'rejected')
                    <span class="text-red-600 font-medium">Ditolak</span>
                  @endif
                </p>

                @if ($latestRequest->note)
                  <p><span class="font-semibold">Catatan:</span> {{ $latestRequest->note }}</p>
                @endif
              </div>
            @endisset

            {{-- Form Pengajuan --}}
            @if ($canTransfer)
              @if (!isset($latestRequest) || $latestRequest->status !== 'pending')
                <form method="POST" action="{{ route('tenant.room-transfer.process') }}" class="space-y-4">
                  @csrf

                  <div>
                    <label for="room_id" class="block font-medium mb-1">Pilih Kamar Baru</label>
                    <select name="room_id" id="room_id" required
                      class="w-full rounded-md border-gray-300 focus:ring focus:ring-blue-200">
                      <option value="">-- Pilih Kamar --</option>
                      @foreach ($availableRooms as $room)
                        <option value="{{ $room->id }}">
                          {{ $room->room_number }} - Rp{{ number_format($room->price, 0, ',', '.') }} / bulan
                        </option>
                      @endforeach
                    </select>
                  </div>

                  <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                    Ajukan Permintaan
                  </button>
                </form>
              @else
                <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-md text-sm">
                  Anda sudah mengajukan permintaan pindah kamar dan sedang menunggu persetujuan.
                </div>
              @endif
            @else
              <div class="bg-red-100 text-red-800 px-4 py-2 rounded-md text-sm">
                💰 Anda belum membayar tagihan terakhir. Silakan selesaikan pembayaran terlebih dahulu sebelum mengajukan pindah kamar.
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
