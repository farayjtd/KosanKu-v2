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
  <link rel="stylesheet" href="/style/font.css">
  @vite('resources/css/app.css')
</head>
<body class="bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-tenant')
    <div id="main-content" class="main-content p-6 md:pt-4">
      <div class="p-4 rounded-xl bg-white flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div>
          <p class="text-xl text-gray-700 font-semibold">
            Selamat datang kembali, <strong class="use-poppins">{{ $tenant->name }}</strong>
          </p>
          @if (isset($remaining_time) && $activeHistory)
            <p class="text-[14px] mt-2 text-gray-600">
              Masa sewa akan habis dalam {{ $activeHistory->end_date }}.<br>Ajukan perpanjangan sekarang!
            </p>
          @endif
        </div>

        @if ($activeHistory)
        <div class="self-end md:self-end mt-6">
          <a href="{{ route('tenant.renewal.direct', ['id' => $activeHistory->id]) }}"
            class="text-sm md:text-base bg-emerald-100 text-emerald-700 font-semibold px-8 py-2 rounded-md hover:bg-[#31c594]">
            <i class="bi bi-arrow-repeat mr-2"></i>
            Ajukan Perpanjangan
          </a>
        </div>
        @endif
      </div>


      {{-- Pesan Sukses --}}
      @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
      @endif
      @if (isset($latestPayment) && $latestPayment->status === 'unpaid')
        <div class="danger">❗ Tagihan Anda belum dibayar. Harap segera lakukan pembayaran.</div>
      @endif

      {{-- Informasi Kamar Saat Ini --}}
      <div class="mt-6 p-6 rounded-2xl bg-white shadow-md">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-xl font-semibold text-gray-700 flex items-center gap-2">
            <i class="bi bi-info-circle text-gray-700"></i>
            Informasi Kamar Saat Ini
          </h2>
          {{-- Tambahan tombol atau status bisa di sini --}}
        </div>

        @if ($activeHistory)
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-start gap-3">
              <i class="bi bi-house text-gray-700text-xl mt-1"></i>
              <div>
                <p class="text-sm text-gray-500">Tipe Kamar</p>
                <p class="text-base font-semibold text-gray-700">{{ $activeHistory->room->type }}</p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <i class="bi bi-door-closed text-gray-700text-xl mt-1"></i>
              <div>
                <p class="text-sm text-gray-500">Nomor Kamar</p>
                <p class="text-base font-semibold text-gray-700">{{ $activeHistory->room->room_number }}</p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <i class="bi bi-currency-dollar text-gray-700text-xl mt-1"></i>
              <div>
                <p class="text-sm text-gray-500">Jumlah Tagihan</p>
                <p class="text-base font-semibold text-gray-700">Rp{{ number_format($activeHistory->room->price, 0, ',', '.') }}</p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <i class="bi bi-clock-history text-gray-700text-xl mt-1"></i>
              <div>
                <p class="text-sm text-gray-500">Masa Aktif Hingga</p>
                <p class="text-base font-semibold text-gray-700">{{ $activeHistory->end_date }}</p>
              </div>
            </div>
          </div>
        @else
          <p class="text-gray-500 text-sm mt-4">Belum ada sewa aktif.</p>
        @endif
      </div>


      {{-- Status Tagihan --}}
      <div class="mt-6 p-6 rounded-2xl bg-white shadow-md">
        <h3>Status Tagihan</h3>
        @if ($latestPayment)
          <table>
            <tr><th>Jumlah Tagihan</th><td>Rp{{ number_format($latestPayment->amount, 0, ',', '.') }}</td></tr>
            <tr><th>Status</th><td>{{ $latestPayment->status === 'paid' ? 'Lunas' : 'Belum Dibayar' }}</td></tr>
            <tr><th>Jatuh Tempo</th><td>{{ $latestPayment->due_date }}</td></tr>
          </table>
        @else
          <p>Belum ada tagihan.</p>
        @endif
      </div>

      {{-- Status Permintaan --}}
      <section>
        <h3>Status Permintaan</h3>
        @if ($latestTransferRequest)
          <table>
            <tr><th>Jenis</th><td>Pindah Kamar</td></tr>
            <tr><th>Status</th><td>{{ ucfirst($latestTransferRequest->status) }}</td></tr>
            @if ($latestTransferRequest->note)
              <tr><th>Catatan</th><td>{{ $latestTransferRequest->note }}</td></tr>
            @endif
          </table>
        @else
          <p>Tidak ada permintaan aktif.</p>
        @endif
      </section>

      {{-- Estimasi Biaya Perpanjangan --}}
      @if ($activeHistory)
        <section>
          <h3>Estimasi Perpanjangan</h3>
          <table>
            <tr><th>Biaya Perpanjangan 1 Bulan</th><td>Rp{{ number_format($activeHistory->room->price, 0, ',', '.') }}</td></tr>
            <tr><th>Tenggat Pengajuan</th><td>{{ Carbon::parse($activeHistory->end_date)->subDays(3)->toDateString() }}</td></tr>
          </table>
        </section>
      @endif

      {{-- Aksi Cepat --}}
      <section>
        <h3>Aksi Cepat</h3>
        <div class="quick-actions">

          <a href="{{ route('tenant.room-transfer.form') }}">🔁 Ajukan Pindah Kamar</a>
          <a href="{{ route('tenant.payment.list') }}">📄 Lihat Tagihan</a>
          @if ($tenant->room && $tenant->room->landboard)
            <a href="https://wa.me/{{ $tenant->room->landboard->phone }}" target="_blank">📞 Hubungi Landboard</a>
          @endif
        </div>
      </section>

      {{-- Kontak Landboard --}}
      <section>
        <h3>Kontak Landboard</h3>
        @if ($tenant->room && $tenant->room->landboard)
          <table>
            <tr><th>Nama Landboard</th><td>{{ $tenant->room->landboard->name }}</td></tr>
            <tr><th>WhatsApp</th><td><a href="https://wa.me/{{ $tenant->room->landboard->phone }}" target="_blank">Hubungi Sekarang</a></td></tr>
          </table>
        @else
          <p>Informasi kontak tidak tersedia.</p>
        @endif
      </section>
    </div>
  </div>
</body>
</html>
