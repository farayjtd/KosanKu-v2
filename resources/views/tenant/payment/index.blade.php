<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tagihan Pembayaran</title>
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
      box-shadow: 0 4px 12px rgba(0,0,0,0.04);
      margin-bottom: 40px;
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

    .pay-button, .submit-button {
      background: #475569;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 13px;
      transition: background 0.2s ease;
    }

    .pay-button:hover, .submit-button:hover {
      background: #334155;
    }

    .method-form {
      margin-top: 10px;
      background: #f9fafb;
      padding: 12px;
      border-radius: 8px;
    }

    .method-form label {
      display: block;
      margin-bottom: 6px;
      font-size: 14px;
      color: #334155;
    }

    .empty {
      text-align: center;
      padding: 40px;
      color: #94a3b8;
    }

    .text-muted {
      font-size: 13px;
      color: #64748b;
      font-style: italic;
    }
  </style>

  <script>
    function toggleMethodForm(id) {
      const allForms = document.querySelectorAll('.method-form');
      allForms.forEach(form => form.style.display = 'none');

      const form = document.getElementById('method-form-' + id);
      if (form) form.style.display = 'block';
    }
  </script>
</head>
<body>
  {{-- Sidebar --}}
  @include('components.sidebar-tenant')

  {{-- Konten Utama --}}
  <div class="main-content">
    <div class="card">
      <h2>Tagihan Pembayaran</h2>

      @if($payments->isEmpty())
        <div class="empty">Belum ada tagihan untuk saat ini.</div>
      @else
        <table>
          <thead>
            <tr>
              <th>Kamar</th>
              <th>Jumlah</th>
              <th>Jatuh Tempo</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($payments as $payment)
              <tr>
                <td>{{ $payment->rentalHistory->room->room_number ?? '-' }}</td>
                <td>Rp{{ number_format($payment->total_amount ?? $payment->amount, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($payment->due_date)->format('d M Y') }}</td>
                <td>
                  <span class="badge {{ $payment->status === 'paid' ? 'paid' : 'unpaid' }}">
                    {{ $payment->status === 'paid' ? 'Sudah Dibayar' : 'Belum Dibayar' }}
                  </span>
                </td>
                <td>
                  @if($payment->status === 'unpaid')
                    <button class="pay-button" onclick="toggleMethodForm({{ $payment->id }})">Bayar</button>

                    <div class="method-form" id="method-form-{{ $payment->id }}" style="display: none;">
                      <form action="{{ route('tenant.payment.createInvoice', ['rentalId' => $payment->rental_history_id]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="rental_id" value="{{ $payment->rental_history_id }}">

                        <label>Pilih Metode Pembayaran:</label>
                        @foreach($channels as $channel)
                          <label>
                            <input type="radio" name="payment_method" value="{{ $channel['code'] }}" required>
                            {{ $channel['name'] }} ({{ $channel['group'] }})
                          </label>
                        @endforeach

                        <br>
                        <button type="submit" class="submit-button">Lanjutkan Pembayaran</button>
                      </form>
                    </div>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
  </div>
</body>
</html>
