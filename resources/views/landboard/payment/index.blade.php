@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cash Payments</title>
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

    .success {
      color: #15803d;
      background: #dcfce7;
      padding: 10px 14px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 14px;
    }

    table {
      width: 100%;
      font-size: 14px;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 10px 12px;
      border-bottom: 1px solid #eee;
      text-align: left;
    }

    th {
      background: #f0e6dc;
      color: #4e3a2c;
    }

    button {
      padding: 8px 14px;
      background: #8d735b;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 13px;
      cursor: pointer;
      transition: background 0.2s ease;
    }

    button:hover {
      background: #6e5947;
    }

    .text-muted {
      color: #999;
      font-style: italic;
    }
  </style>
</head>
<body>

  {{-- Sidebar untuk Landboard --}}
  @include('components.sidebar-landboard')

  <div class="main-content">
    <div class="card">
      <h2>Cash Payments</h2>

      {{-- Pesan sukses --}}
      @if (session('success'))
        <div class="success">{{ session('success') }}</div>
      @endif

      <table>
        <thead>
          <tr>
            <th>Username</th>
            <th>Tenant Name</th>
            <th>Room</th>
            <th>Due Date</th>
            <th>Amount</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($payments as $payment)
            <tr>
              <td>{{ $payment->rentalHistory->tenant->account->username }}</td>
              <td>{{ $payment->rentalHistory->tenant->name ?: 'Belum diisi' }}</td>
              <td>{{ $payment->rentalHistory->room->room_number }}</td>
              <td>{{ Carbon::parse($payment->due_date)->format('d M Y') }}</td>
              <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
              <td>
                <form action="{{ route('landboard.payments.markPaid', $payment->id) }}" method="POST">
                  @csrf
                  <button type="submit">Mark as Paid</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align: center; color: #888;">No pending cash payments.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
