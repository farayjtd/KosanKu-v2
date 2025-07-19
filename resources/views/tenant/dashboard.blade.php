@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>KosanKu - Dashboard Tenant</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  @vite('resources/css/app.css')
</head>
<body class="bg-cover bg-no-repeat bg-center use-poppins-normal" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-tenant')

    <div id="main-content" class="main-content p-6 md:pt-4 w-full">

      @if ($tenant->status === 'nonaktif')
      <div class="flex items-center justify-center min-h-full bg-white rounded-xl shadow-md">
        <div class="p-8 text-center mx-auto ">
          <i class="bi bi-exclamation-circle text-4xl text-gray-400 mb-4"></i>
          <p class="text-lg text-gray-600 font-semibold">Sewa Anda sedang tidak aktif.</p>
          <p class="text-gray-500 mt-2">Silakan hubungi Landboard untuk informasi lebih lanjut atau lakukan pendaftaran sewa baru.</p>
        </div>
      </div>
      @else

        {{-- Welcome Card --}}
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
              <p class="text-left text-xl text-gray-700 mb-2">
                Selamat datang kembali, <strong class="use-poppins text-gray">{{ $tenant->name }}</strong>
              </p>
            </div>
          </div>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
          <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
              <i class="bi bi-check-circle-fill mr-2"></i>
              <span class="text-sm md:text-base">{{ session('success') }}</span>
            </div>
          </div>
        @endif

        @if (isset($latestPayment) && $latestPayment->status === 'unpaid')
          <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
              <i class="bi bi-exclamation-triangle-fill mr-2"></i>
              <span class="text-sm md:text-base">
                <strong>Tagihan Belum Dibayar!</strong> Harap segera lakukan pembayaran untuk menghindari denda.
              </span>
            </div>
          </div>
        @endif

        {{-- Grid Utama --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
          {{-- Informasi Kamar --}}
          <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Informasi Kamar Saat Ini</h2>
            @if ($activeHistory)
              <div class="space-y-2">
                <div class="flex justify-between py-2 border-b border-gray-100">
                  <span class="text-sm text-gray-600">Tipe Kamar</span>
                  <span class="font-semibold text-sm text-gray-700">{{ $activeHistory->room->type }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                  <span class="text-sm text-gray-600">Nomor Kamar</span>
                  <span class="font-semibold text-sm text-gray-700">{{ $activeHistory->room->room_number }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                  <span class="text-sm text-gray-600">Harga Sewa</span>
                  <span class="font-semibold text-sm text-gray-700">Rp{{ number_format($activeHistory->room->price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2">
                  <span class="text-sm text-gray-600">Masa Aktif Hingga</span>
                  <span class="font-semibold text-sm text-gray-700">{{ $activeHistory->end_date }}</span>
                </div>
              </div>
            @else
              <div class="text-center py-8 text-gray-500">Belum ada sewa aktif</div>
            @endif
          </div>

          {{-- Status Tagihan --}}
          <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Status Tagihan</h3>
            @if ($latestPayment)
              <div class="space-y-2">
                <div class="flex justify-between py-2 border-b border-gray-100">
                  <span class="text-sm text-gray-600">Jumlah Tagihan</span>
                  <span class="font-semibold text-sm text-gray-700">Rp{{ number_format($latestPayment->amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                  <span class="text-sm text-gray-600">Status</span>
                  @if ($latestPayment->status === 'paid')
                    <span class="inline-flex items-center bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                      <i class="bi bi-check-circle-fill mr-1"></i>Lunas
                    </span>
                  @else
                    <span class="inline-flex items-center bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">
                      <i class="bi bi-exclamation-circle-fill mr-1"></i>Belum Dibayar
                    </span>
                  @endif
                </div>
                <div class="flex justify-between py-2">
                  <span class="text-sm text-gray-600">Jatuh Tempo</span>
                  <span class="font-semibold text-sm text-gray-700">{{ $latestPayment->due_date }}</span>
                </div>
              </div>
            @else
              <div class="text-center py-8 text-gray-500">Belum ada tagihan</div>
            @endif
          </div>
        </div>

        {{-- Status Permintaan + Estimasi + Kontak --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
          {{-- Status Permintaan --}}
          <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Status Permintaan</h3>
            @if ($latestTransferRequest)
              <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex justify-between mb-3">
                  <span class="text-sm font-medium text-blue-700">Permintaan Pindah Kamar</span>
                  <span class="inline-flex items-center bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                    {{ ucfirst($latestTransferRequest->status) }}
                  </span>
                </div>
                @if ($latestTransferRequest->note)
                  <p class="text-sm text-blue-600">Catatan: {{ $latestTransferRequest->note }}</p>
                @endif
              </div>
            @else
              <div class="text-center py-6 text-gray-500">Tidak ada permintaan aktif</div>
            @endif
          </div>

          {{-- Estimasi Perpanjangan --}}
          @if ($activeHistory)
            <div class="bg-white rounded-xl shadow-lg p-6">
              <h3 class="text-lg font-semibold text-gray-700 mb-4">Estimasi Perpanjangan</h3>
              <div class="space-y-2">
                <div class="flex justify-between py-2 border-b border-gray-100">
                  <span class="text-sm text-gray-600">Biaya Perpanjangan 1 Bulan</span>
                  <span class="font-semibold text-sm text-gray-700">Rp{{ number_format($activeHistory->room->price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2">
                  <span class="text-sm text-gray-600">Tenggat Pengajuan</span>
                  <span class="font-semibold text-sm text-gray-700">{{ Carbon::parse($activeHistory->end_date)->subDays(3)->toDateString() }}</span>
                </div>
              </div>
            </div>
          @endif
        </div>

        {{-- Kontak dan Aksi --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          {{-- Kontak Landboard --}}
          <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Kontak Landboard</h3>
            @if ($tenant->room && $tenant->room->landboard)
              <div class="space-y-4">
                <div class="flex justify-between py-2 border-b border-gray-100">
                  <span class="text-sm text-gray-600">Nama Landboard</span>
                  <span class="font-semibold text-sm text-gray-700">{{ $tenant->room->landboard->name }}</span>
                </div>
                <div class="flex justify-between py-2">
                  <span class="text-sm text-gray-600">WhatsApp</span>
                  <a href="https://wa.me/{{ $tenant->room->landboard->phone }}" target="_blank"
                    class="inline-flex items-center bg-green-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-green-600 transition-colors">
                    <i class="bi bi-whatsapp mr-2"></i>Hubungi Sekarang
                  </a>
                </div>
              </div>
            @else
              <div class="text-center py-6 text-gray-500">Informasi kontak tidak tersedia</div>
            @endif
          </div>

          {{-- Aksi Cepat --}}
          <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <a href="{{ route('tenant.room-transfer.form') }}" class="flex items-center justify-center bg-green-50 hover:bg-green-100 text-green-700 p-4 rounded-lg transition hover:-translate-y-1 hover:shadow-md">
                <i class="bi bi-arrow-left-right text-sm mr-2"></i>
                <span class="font-medium text-sm">Ajukan Pindah Kamar</span>
              </a>
              <a href="{{ route('tenant.payment.list') }}" class="flex items-center justify-center bg-green-50 hover:bg-green-100 text-green-700 p-4 rounded-lg transition hover:-translate-y-1 hover:shadow-md">
                <i class="bi bi-receipt text-sm mr-2"></i>
                <span class="font-medium text-sm">Lihat Tagihan</span>
              </a>
              @if ($tenant->room && $tenant->room->landboard)
                <a href="https://wa.me/{{ $tenant->room->landboard->phone }}" target="_blank"
                  class="flex items-center justify-center bg-emerald-50 hover:bg-emerald-100 text-emerald-700 p-4 rounded-lg transition hover:-translate-y-1 hover:shadow-md">
                  <i class="bi bi-telephone text-sm mr-2"></i>
                  <span class="font-medium text-sm">Hubungi Landboard</span>
                </a>
              @endif
            </div>
          </div>
        </div>

      @endif
    </div>
  </div>
</body>
</html>