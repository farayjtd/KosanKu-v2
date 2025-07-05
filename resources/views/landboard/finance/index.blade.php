@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Riwayat Keuangan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      background: #f5f3f0;
    }

    .main-content {
      flex: 1;
      padding: 30px;
    }

    .card {
      background: #fffaf6;
      padding: 25px;
      border-radius: 12px;
      max-width: 1000px;
      margin: auto;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    h2 {
      color: #5a4430;
      text-align: center;
      margin-bottom: 20px;
    }

    .section-title {
      font-size: 16px;
      font-weight: bold;
      margin: 30px 0 10px;
      color: #4e3a2c;
    }

    table {
      width: 100%;
      font-size: 14px;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 10px;
      border-bottom: 1px solid #eee;
    }

    th {
      background: #f0e6dc;
      color: #4e3a2c;
      text-align: left;
    }

    .filter-form {
      margin-bottom: 25px;
      display: flex;
      gap: 10px;
      align-items: center;
      flex-wrap: wrap;
    }

    label {
      font-weight: 600;
      margin-right: 5px;
    }

    select, input[type="text"] {
      padding: 6px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    button {
      padding: 7px 14px;
      background: #8d735b;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 13px;
      cursor: pointer;
    }

    button:hover {
      background: #6e5947;
    }

    .empty {
      text-align: center;
      color: #888;
    }
  </style>
</head>
<body>

  {{-- Sidebar --}}
  @include('components.sidebar-landboard')

  <div class="main-content">
    <div class="card">
      <h2>Riwayat Keuangan</h2>

      {{-- Filter --}}
      <form method="GET" class="filter-form">
        <label>Urutkan:</label>
        <select name="sort" onchange="this.form.submit()">
          <option value="">-- Pilih Urutan --</option>
          <option value="amount_desc" {{ request('sort') == 'amount_desc' ? 'selected' : '' }}>Jumlah Bayar Terbesar</option>
          <option value="amount_asc" {{ request('sort') == 'amount_asc' ? 'selected' : '' }}>Jumlah Bayar Terkecil</option>
          <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Pembayaran Terbaru</option>
          <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Pembayaran Terlama</option>
        </select>

        <input type="text" name="username" placeholder="Cari username..." value="{{ request('username') }}">
        <button type="submit">Filter</button>
      </form>

      {{-- Riwayat Pemasukan --}}
      <div class="section-title">Riwayat Pemasukan</div>
      <table>
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Tenant</th>
            <th>Kamar</th>
            <th>Jumlah</th>
            <th>Metode</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($incomePayments as $payment)
            <tr>
              <td>{{ Carbon::parse($payment->paid_at)->format('d M Y') }}</td>
              <td>
                {{ $payment->rentalHistory->tenant?->name ?? 'Belum isi nama' }}<br>
                <small>{{ $payment->rentalHistory->tenant?->account?->username ?? '[Username Tidak Ada]' }}</small>
              </td>
              <td>{{ $payment->rentalHistory->room?->room_number ?? '[Kamar Terhapus]' }}</td>
              <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
              <td>{{ ucfirst($payment->payment_method ?? '-') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="empty">Belum ada pemasukan.</td>
            </tr>
          @endforelse
        </tbody>
      </table>

      {{-- Riwayat Pengeluaran --}}
      <div class="section-title">Riwayat Pengeluaran</div>
      <table>
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Tenant</th>
            <th>Kamar</th>
            <th>Jumlah</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($expensePayments as $payment)
            <tr>
              <td>{{ Carbon::parse($payment->paid_at)->format('d M Y') }}</td>
              <td>
                {{ $payment->rentalHistory->tenant?->name ?? 'Belum isi nama' }}<br>
                <small>{{ $payment->rentalHistory->tenant?->account?->username ?? '[Username Tidak Ada]' }}</small>
              </td>
              <td>{{ $payment->rentalHistory->room?->room_number ?? '[Kamar Terhapus]' }}</td>
              <td style="color: red;">Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="empty">Belum ada pengeluaran.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
