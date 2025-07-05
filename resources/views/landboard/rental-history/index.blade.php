<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Sewa Tenant</title>
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
      max-width: 900px;
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
    }

    th {
      text-align: left;
      background: #f0e6dc;
      color: #4e3a2c;
    }

    .status {
      padding: 4px 8px;
      border-radius: 6px;
      font-weight: 500;
      display: inline-block;
    }

    .status.ongoing {
      background: #d1fae5;
      color: #065f46;
    }

    .status.finished {
      background: #fee2e2;
      color: #991b1b;
    }

    .status.upcoming {
      background: #fef9c3;
      color: #92400e;
    }

    .sort-select {
      padding: 6px 10px;
      margin-bottom: 20px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    .empty-row {
      text-align: center;
      color: #999;
    }
  </style>
</head>
<body>

  {{-- Sidebar Landboard --}}
  @include('components.sidebar-landboard')

  <div class="main-content">
    <div class="card">
      <h2>Riwayat Sewa Semua Tenant</h2>

      {{-- Filter Urutan --}}
      <form method="GET">
        <select name="sort" onchange="this.form.submit()" class="sort-select">
          <option value="start_desc" {{ $sort == 'start_desc' ? 'selected' : '' }}>Terbaru</option>
          <option value="start_asc" {{ $sort == 'start_asc' ? 'selected' : '' }}>Terlama</option>
          <option value="tenant_asc" {{ $sort == 'tenant_asc' ? 'selected' : '' }}>Tenant A-Z</option>
          <option value="tenant_desc" {{ $sort == 'tenant_desc' ? 'selected' : '' }}>Tenant Z-A</option>
          <option value="room_asc" {{ $sort == 'room_asc' ? 'selected' : '' }}>Kamar A-Z</option>
          <option value="room_desc" {{ $sort == 'room_desc' ? 'selected' : '' }}>Kamar Z-A</option>
        </select>
      </form>

      {{-- Tabel Riwayat --}}
      <table>
        <thead>
          <tr>
            <th>Nama Tenant</th>
            <th>Nama Kamar</th>
            <th>Mulai</th>
            <th>Berakhir</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($histories as $history)
            <tr>
              <td>{{ $history->tenant?->name ?? '[Tenant dihapus]' }}</td>
              <td>{{ $history->room?->room_number ?? '[Kamar dihapus]' }}</td>
              <td>{{ \Carbon\Carbon::parse($history->start_date)->format('d M Y') }}</td>
              <td>{{ $history->end_date ? \Carbon\Carbon::parse($history->end_date)->format('d M Y') : '—' }}</td>
              <td>
                <span class="status {{
                  $history->computed_status === 'Sedang Berjalan' ? 'ongoing' :
                  ($history->computed_status === 'Selesai' ? 'finished' : 'upcoming')
                }}">
                  {{ $history->computed_status }}
                </span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="empty-row">Belum ada riwayat sewa ditemukan.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
