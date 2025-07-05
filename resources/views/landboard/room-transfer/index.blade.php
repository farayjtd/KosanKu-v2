<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Permintaan Pindah Kamar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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

    table {
      width: 100%;
      font-size: 14px;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 10px;
      border-bottom: 1px solid #eee;
      vertical-align: top;
      text-align: left;
    }

    th {
      background: #f0e6dc;
      color: #4e3a2c;
    }

    .success, .error {
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 15px;
      font-size: 14px;
    }

    .success {
      color: #15803d;
      background: #dcfce7;
    }

    .error {
      color: #b91c1c;
      background: #fee2e2;
    }

    form {
      margin-top: 5px;
    }

    textarea {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border-radius: 6px;
      border: 1px solid #ccc;
      resize: vertical;
      font-size: 13px;
    }

    button {
      padding: 6px 12px;
      background: #8d735b;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 13px;
      cursor: pointer;
      margin-right: 6px;
      margin-top: 8px;
      transition: background 0.3s ease;
    }

    button:hover {
      background: #6e5947;
    }

    .btn-reject {
      background: #ef4444;
    }

    .btn-reject:hover {
      background: #b91c1c;
    }

    .note {
      font-size: 13px;
      color: #555;
      font-style: italic;
    }

    .status {
      font-weight: 600;
    }

    .status.pending {
      color: orange;
    }

    .status.approved {
      color: green;
    }

    .status.rejected {
      color: red;
    }

    @media (max-width: 768px) {
      .main-content {
        padding: 20px;
      }

      table, thead, tbody, th, td, tr {
        display: block;
      }

      th {
        background: none;
        color: #333;
      }

      td {
        border-bottom: 1px solid #ddd;
        padding-left: 0;
        margin-bottom: 10px;
      }

      tr {
        margin-bottom: 20px;
      }
    }
  </style>
</head>
<body>

  @include('components.sidebar-landboard')

  <div class="main-content">
    <div class="card">
      <h2>Permintaan Pindah Kamar</h2>

      @if (session('success'))
        <div class="success">{{ session('success') }}</div>
      @elseif (session('error'))
        <div class="error">{{ session('error') }}</div>
      @endif

      <table>
        <thead>
          <tr>
            <th>Username</th>
            <th>Tenant</th>
            <th>Dari</th>
            <th>Ke</th>
            <th>Refund</th>
            <th>Catatan</th>
            <th>Status</th>
            <th>Tindakan</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($requests as $request)
            <tr>
              <td>{{ $request->tenant->account->username }}</td>
              <td>{{ $request->tenant->name ?: 'Belum diisi' }}</td>
              <td>{{ $request->currentRoom->room_number }}</td>
              <td>{{ $request->newRoom->room_number }}</td>
              <td>Rp{{ number_format($request->manual_refund, 0, ',', '.') }}</td>
              <td class="note">{{ $request->note ?: '-' }}</td>
              <td>
                <span class="status {{ $request->status }}">
                  @switch($request->status)
                    @case('pending') Menunggu @break
                    @case('approved') Disetujui @break
                    @case('rejected') Ditolak @break
                  @endswitch
                </span>
              </td>
              <td>
                @if ($request->status === 'pending')
                  <form method="POST" action="{{ route('landboard.room-transfer.handle', $request->id) }}">
                    @csrf
                    <textarea name="note" placeholder="Catatan persetujuan atau penolakan" required></textarea>
                    <button type="submit" name="action_type" value="approve">Setujui</button>
                    <button type="submit" name="action_type" value="reject" class="btn-reject">Tolak</button>
                  </form>
                @else
                  <span class="note">-</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" style="text-align: center; color: #888;">Tidak ada permintaan pindah kamar.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
