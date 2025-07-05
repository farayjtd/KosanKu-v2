<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Aktivasi Ulang Tenant</title>
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
      max-width: 600px;
      margin: auto;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    h2 {
      margin-bottom: 20px;
      color: #5a4430;
      text-align: center;
    }

    label {
      display: block;
      margin-top: 14px;
      font-weight: 600;
      color: #6b4e3d;
    }

    input, select {
      width: 100%;
      padding: 10px;
      margin-top: 4px;
      border-radius: 8px;
      border: 1px solid #cfc4b5;
      font-size: 14px;
      background: #fdfdfb;
      color: #3f3f3f;
      box-sizing: border-box;
    }

    button {
      margin-top: 30px;
      padding: 12px 20px;
      background: #8d735b;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 15px;
      cursor: pointer;
      transition: background 0.2s ease;
    }

    button:hover {
      background: #6e5947;
    }

    .success {
      color: #15803d;
      background: #dcfce7;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 15px;
    }

    .error {
      color: #b91c1c;
      background: #fee2e2;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 15px;
    }

    @media (max-width: 768px) {
      .card {
        padding: 20px;
        margin: 20px;
      }

      .main-content {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  @include('components.sidebar-landboard')

  <div class="main-content">
    <div class="card">
      <h2>Aktivasi Ulang Tenant: {{ $tenant->account->username }}</h2>

      @if(session('success'))
        <div class="success">{{ session('success') }}</div>
      @endif

      @if($errors->any())
        <div class="error">
          <ul style="padding-left: 18px; margin: 0;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('landboard.tenants.reactivate', $tenant->id) }}" method="POST">
        @csrf

        <label for="room_id">Pilih Kamar</label>
        <select name="room_id" id="room_id" required>
          <option value="">-- Pilih Kamar --</option>
          @foreach ($rooms as $room)
            <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
              Kamar {{ $room->room_number }} (Rp{{ number_format($room->price, 0, ',', '.') }})
            </option>
          @endforeach
        </select>

        <label for="duration_months">Durasi Sewa</label>
        <select name="duration_months" id="duration_months" required>
          <option value="0.1" {{ old('duration_months') == 0.1 ? 'selected' : '' }}>5 hari (uji coba)</option>
          @foreach ([1, 3, 6, 12] as $bulan)
            <option value="{{ $bulan }}" {{ old('duration_months') == $bulan ? 'selected' : '' }}>
              {{ $bulan }} bulan
            </option>
          @endforeach
        </select>

        <label for="start_date">Tanggal Masuk</label>
        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required>

        <button type="submit">Aktifkan Kembali</button>
      </form>
    </div>
  </div>

</body>
</html>
