@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KosanKu - Dashboard Landboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
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
      padding: 30px;
    }

    h2 {
      margin-top: 0;
      color: #5b4636;
      font-size: 2em;
    }

    .overview {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }

    .card {
      background: #fffaf6;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.04);
    }

    .card h3 {
      margin: 0 0 10px;
      color: #5b4636;
      font-size: 16px;
    }

    .card p {
      font-size: 22px;
      margin: 0;
      font-weight: bold;
      color: #3f3f3f;
    }

    table {
      width: 100%;
      margin-top: 15px;
      border-collapse: collapse;
    }

    th, td {
      border: 1px solid #e5e5e5;
      padding: 8px 12px;
      font-size: 13px;
    }

    th {
      background: #f1ebe6;
      text-align: left;
    }

    .section {
      margin-top: 40px;
    }

    .section h3 {
      color: #4e3b2c;
      margin-bottom: 10px;
    }

    .chart-container {
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.04);
      margin-top: 40px;
    }
  </style>
</head>
<body>

  @include('components.sidebar-landboard')

  <div class="main-content">
    <h2>Halo, selamat pagi 👋</h2>
    <p>Selamat datang kembali, <strong>{{ Auth::user()->landboard->name }}</strong>!</p>

    {{-- Statistika --}}
    <div class="overview">
      <div class="card"><h3>Total Kamar</h3><p>{{ $totalRooms }}</p></div>
      <div class="card"><h3>Total Tenant</h3><p>{{ $totalTenants }}</p></div>
      <div class="card"><h3>Kamar Terisi</h3><p>{{ $occupiedRooms }}</p></div>
      <div class="card"><h3>Kamar Kosong</h3><p>{{ $totalRooms - $occupiedRooms }}</p></div>
      <div class="card"><h3>Pemasukan Bulan Ini</h3><p>Rp{{ number_format($monthlyIncome, 0, ',', '.') }}</p></div>
      <div class="card"><h3>Pemasukan Tahun Ini</h3><p>Rp{{ number_format($yearlyIncome, 0, ',', '.') }}</p></div>
    </div>

    {{-- Grafik Penghasilan --}}
    <div class="chart-container">
      <h3>Grafik Pemasukan Bulanan</h3>
      <canvas id="incomeChart" height="100"></canvas>
    </div>

    {{-- Transaksi Terbaru --}}
    <div class="section">
      <h3>Transaksi Terbaru</h3>
      <table>
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Tenant</th>
            <th>Nominal</th>
            <th>Metode</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentPayments as $payment)
            <tr>
              <td>{{ $payment->created_at->format('d M Y') }}</td>
              <td>{{ $payment->tenant->name }}</td>
              <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
             <td>{{ $payment->payment_method ? ucfirst($payment->payment_method) : '-' }}</td>
            </tr>
          @empty
            <tr><td colspan="4" style="text-align:center; color:#888;">Belum ada transaksi.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Tenant Baru --}}
    <div class="section">
      <h3>Tenant Baru</h3>
      <table>
        <thead>
          <tr><th>Nama</th><th>Kamar</th><th>Masuk</th></tr>
        </thead>
        <tbody>
          @forelse($newTenants as $tenant)
            <tr>
              <td>{{ $tenant->name }}</td>
              <td>{{ $tenant->room->room_number ?? '-' }}</td>
              <td>
                {{ $tenant->rentalHistories->last()?->start_date
                  ? Carbon::parse($tenant->rentalHistories->last()->start_date)->format('d M Y')
                  : '-' 
                }}
              </td>
            </tr>
          @empty
            <tr><td colspan="3" style="text-align:center; color:#888;">Belum ada tenant baru.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Tenant Belum Bayar --}}
    <div class="section">
      <h3>Tagihan Belum Lunas</h3>
      <table>
        <thead>
          <tr><th>Tenant</th><th>Jatuh Tempo</th><th>Nominal</th></tr>
        </thead>
        <tbody>
          @forelse($unpaidPayments as $bill)
            <tr>
              <td>{{ $bill->tenant->name }}</td>
              <td>{{ Carbon::parse($bill->due_date)->format('d M Y') }}</td>
              <td>Rp{{ number_format($bill->amount, 0, ',', '.') }}</td>
            </tr>
          @empty
            <tr><td colspan="3" style="text-align:center; color:#999;">Tidak ada tagihan belum lunas.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>

  {{-- ChartJS --}}
  <script>
    const ctx = document.getElementById('incomeChart');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: {!! json_encode($monthlyLabels) !!},
        datasets: [{
          label: 'Pemasukan',
          data: {!! json_encode($monthlyAmounts) !!},
          backgroundColor: '#8b5e3c',
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return 'Rp' + value.toLocaleString('id-ID');
              }
            }
          }
        }
      }
    });
  </script>

</body>
</html>
