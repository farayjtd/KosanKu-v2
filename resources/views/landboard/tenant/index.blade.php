<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Tenant</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f5f3f0;
      display: flex;
    }

    .main-content {
      flex: 1;
      padding: 30px;
    }

    h2 {
      color: #5a4430;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: #fffaf6;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
    }

    th, td {
      border: 1px solid #e3dcd6;
      padding: 12px;
      text-align: left;
      font-size: 14px;
      color: #4a3b30;
    }

    th {
      background-color: #e9e2da;
    }

    .action-buttons a,
    .action-buttons form button {
      background: #8d735b;
      color: white;
      border: none;
      padding: 6px 10px;
      font-size: 13px;
      border-radius: 6px;
      text-decoration: none;
      cursor: pointer;
      margin-right: 5px;
      transition: background 0.2s ease;
    }

    .action-buttons a:hover,
    .action-buttons form button:hover {
      background: #6e5947;
    }

    .action-buttons form {
      display: inline;
    }

    @media (max-width: 768px) {
      .main-content {
        padding: 20px;
      }

      table, thead, tbody, th, td, tr {
        display: block;
      }

      td {
        padding: 10px;
      }

      .action-buttons {
        margin-top: 10px;
      }
    }

    .filter-form {
      margin-bottom: 20px;
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .filter-form input,
    .filter-form select {
      padding: 6px 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .success-message {
      color: green;
      font-weight: bold;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

  @include('components.sidebar-landboard')

  <div class="main-content">
    <h2>Daftar Tenant</h2>

    @if(session('success'))
      <div class="success-message">{{ session('success') }}</div>
    @endif

    <form method="GET" class="filter-form">
      <input type="text" name="search" placeholder="Cari nama / username"
             value="{{ request('search') }}">

      <select name="sort" onchange="this.form.submit()">
        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
        <option value="username_asc" {{ request('sort') == 'username_asc' ? 'selected' : '' }}>Username A-Z</option>
        <option value="username_desc" {{ request('sort') == 'username_desc' ? 'selected' : '' }}>Username Z-A</option>
      </select>

      <select name="status" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
      </select>
    </form>

    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Username</th>
          <th>Nama Lengkap</th>
          <th>No Kamar</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($tenants as $index => $tenant)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $tenant->account->username }}</td>
            <td>{{ $tenant->name ?? 'Belum diisi' }}</td>
            <td>{{ $tenant->room->room_number ?? '-' }}</td>
            <td>{{ ucfirst($tenant->status ?? 'aktif') }}</td>
            <td class="action-buttons">
              <a href="{{ route('landboard.tenants.show', $tenant->id) }}">Detail</a>

              @if($tenant->status === 'nonaktif')
                <a href="{{ route('landboard.tenants.reactivate.form', $tenant->id) }}">Aktifkan</a>
              @else
                <a href="{{ route('landboard.tenants.edit', $tenant->id) }}">Edit</a>
                <form action="{{ route('landboard.tenants.destroy', $tenant->id) }}" method="POST" onsubmit="return confirm('Yakin nonaktifkan tenant ini?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit">Nonaktifkan</button>
                </form>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</body>
</html>
