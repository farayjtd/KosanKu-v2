@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Sewa Saya</title>
  <style>
    body {
      margin: 0;
      font-family: sans-serif;
      display: flex;
      background: #f1f5f9;
    }

    .main-content {
      flex: 1;
      padding: 30px;
      background: #f8fafc;
    }

    .card {
      background: white;
      padding: 28px;
      border-radius: 12px;
      max-width: 1000px;
      margin: auto;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    h2 {
      margin-top: 0;
      color: #1e293b;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 12px 14px;
      border-bottom: 1px solid #e2e8f0;
      text-align: left;
      font-size: 14px;
    }

    th {
      background: #f1f5f9;
      color: #334155;
    }

    tr:hover {
      background-color: #f9fafb;
    }

    .badge {
      padding: 4px 10px;
      border-radius: 8px;
      font-size: 13px;
      color: white;
      display: inline-block;
      min-width: 80px;
      text-align: center;
    }

    .paid { background-color: #16a34a; }
    .unpaid { background-color: #dc2626; }
    .running { background-color: #2563eb; }
    .upcoming { background-color: #f59e0b; }
    .ended { background-color: #64748b; }
    .cancelled { background-color: #b91c1c; }

    .empty {
      text-align: center;
      padding: 40px;
      color: #94a3b8;
      font-size: 15px;
    }
  </style>
</head>
<body>

  @include('components.sidebar-tenant')

  <div class="main-content">
    <div class="card">
      <h2>Riwayat Sewa Saya</h2>

      @if ($histories->isEmpty())
        <div class="empty">Belum ada data sewa yang tercatat.</div>
      @else
        <table>
          <thead>
            <tr>
              <th>No. Kamar</th>
              <th>Harga / Bulan</th>
              <th>Denda</th>
              <th>Status Pembayaran</th>
              <th>Status Sewa</th>
              <th>Tanggal Masuk</th>
              <th>Tanggal Keluar</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($histories as $history)
              @php
                $payment = $history->payment;
                $statusClass = match($history->computed_status) {
                    'Sedang Berjalan' => 'running',
                    'Belum Dimulai' => 'upcoming',
                    'Selesai' => 'ended',
                    default => 'cancelled',
                };
              @endphp
              <tr>
                <td>{{ $history->room->room_number }}</td>
                <td>Rp{{ number_format($history->room->price, 0, ',', '.') }}</td>
                <td>
                  @if ($payment && $payment->is_penalty)
                    Rp{{ number_format($payment->amount, 0, ',', '.') }}
                  @else
                    Rp0
                  @endif
                </td>
                <td>
                  @if ($payment)
                    <span class="badge {{ $payment->status === 'paid' ? 'paid' : 'unpaid' }}">
                      {{ $payment->status === 'paid' ? 'Sudah Dibayar' : 'Belum Dibayar' }}
                    </span>
                  @else
                    <span class="badge unpaid">Belum Dibayar</span>
                  @endif
                </td>
                <td>
                  <span class="badge {{ $statusClass }}">{{ $history->computed_status }}</span>
                </td>
                <td>{{ Carbon::parse($history->start_date)->format('d M Y') }}</td>
                <td>{{ $history->end_date ? Carbon::parse($history->end_date)->format('d M Y') : '-' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif

    </div>
  </div>

</body>
</html>
