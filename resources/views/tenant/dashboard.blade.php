@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>KosanKu - Dashboard Tenant</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      background: #f8f5f2;
      display: flex;
    }

    .sidebar {
      width: 240px;
      background: #e6e1dc;
      padding: 20px;
      height: 100vh;
    }

    .main-content {
      flex: 1;
      max-width: 900px;
      margin: auto;
      padding: 30px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
    }

    h2, h3 {
      margin-top: 0;
      color: #5b4636;
    }

    section {
      margin-bottom: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    th, td {
      padding: 10px 8px;
      border-bottom: 1px solid #eee;
      text-align: left;
    }

    th {
      background: #f3f2f1;
      color: #7f6a49;
    }

    .alert-success {
      background: #d4edda;
      padding: 10px 15px;
      border-left: 5px solid #28a745;
      margin-bottom: 20px;
      border-radius: 4px;
      color: #155724;
    }

    .warning {
      background: #fff3cd;
      padding: 10px 15px;
      border-left: 5px solid #ffc107;
      margin-bottom: 20px;
      border-radius: 4px;
      color: #856404;
    }

    .danger {
      background: #f8d7da;
      padding: 10px 15px;
      border-left: 5px solid #dc3545;
      margin-bottom: 20px;
      border-radius: 4px;
      color: #721c24;
    }

    .quick-actions a {
      display: inline-block;
      margin: 8px 10px 8px 0;
      padding: 10px 15px;
      background: #e6e1dc;
      color: #5b4636;
      text-decoration: none;
      border-radius: 5px;
    }
  </style>
</head>
<body>

  <div class="sidebar">
    @include('components.sidebar-tenant')
  </div>

  <div class="main-content">
    <h2>Halo, {{ $tenant->name }}!
      @if ($tenant->status === 'nonaktif')
        <span style="font-size: 16px; color: #b91c1c; font-weight: normal;">(Akun Nonaktif)</span>
      @endif
    </h2>

    @if (session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- Peringatan --}}
    @if (isset($remaining_time) && $activeHistory)
      <div class="warning">🌟 Masa sewa akan habis dalam {{ $remaining_time }}. Ajukan perpanjangan sekarang!</div>
    @endif

    @if (isset($latestPayment) && $latestPayment->status === 'unpaid')
      <div class="danger">❗ Tagihan Anda belum dibayar. Harap segera lakukan pembayaran.</div>
    @endif

    {{-- Informasi Kamar Saat Ini --}}
    <section>
      <h3>Informasi Kamar Saat Ini</h3>
      @if ($activeHistory)
        <table>
          <tr><th>Nomor Kamar</th><td>{{ $activeHistory->room->room_number }}</td></tr>
          <tr><th>Harga per Bulan</th><td>Rp{{ number_format($activeHistory->room->price, 0, ',', '.') }}</td></tr>
          <tr><th>Sisa Waktu</th><td>Sisa {{ $remaining_time }}, sewa berakhir pada {{ $activeHistory->end_date }}</td></tr>
        </table>
      @else
        <p>Belum ada sewa aktif.</p>
      @endif
    </section>

    {{-- Status Tagihan --}}
    <section>
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
    </section>

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
        @if ($activeHistory)
          <a href="{{ route('tenant.renewal.direct', ['id' => $activeHistory->id]) }}">🔄 Ajukan Perpanjangan</a>
        @endif
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
</body>
</html>
