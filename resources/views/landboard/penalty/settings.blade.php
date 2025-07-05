<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pengaturan Penalti</title>
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
      max-width: 700px;
      margin: auto;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    h2 {
      color: #5a4430;
      text-align: center;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-top: 14px;
      font-weight: 600;
      color: #6b4e3d;
    }

    input[type="text"],
    input[type="number"] {
      width: 100%;
      padding: 10px;
      margin-top: 4px;
      border-radius: 8px;
      border: 1px solid #cfc4b5;
      font-size: 14px;
      background: #fdfdfb;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .checkbox-group {
      margin-top: 14px;
    }

    .checkbox-group label {
      font-weight: 600;
      color: #6b4e3d;
    }

    .checkbox-group input {
      margin-right: 8px;
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
  </style>
</head>
<body>

  {{-- Sidebar Landboard --}}
  @include('components.sidebar-landboard')

  <div class="main-content">
    <div class="card">
      <h2>Pengaturan Penalti</h2>

      {{-- Pesan Sukses --}}
      @if(session('success'))
        <div class="success">{{ session('success') }}</div>
      @endif

      {{-- Validasi Error --}}
      @if($errors->any())
        <div class="error">
          <ul style="padding-left: 18px; margin: 0;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Form Pengaturan --}}
      <form action="{{ route('landboard.penalty.update') }}" method="POST">
        @csrf

        <div class="checkbox-group">
          <label>
            <input type="checkbox" name="is_penalty_enabled" {{ $landboard->is_penalty_enabled ? 'checked' : '' }}>
            Aktifkan Penalti
          </label>
        </div>

        <div class="form-group">
          <label>Jumlah Denda Telat Bayar (Rp)</label>
          <input type="number" name="late_fee_amount" value="{{ old('late_fee_amount', $landboard->late_fee_amount) }}">
        </div>

        <div class="form-group">
          <label>Jumlah Hari Setelah Jatuh Tempo Sebelum Denda (hari)</label>
          <input type="number" name="late_fee_days" value="{{ old('late_fee_days', $landboard->late_fee_days) }}">
        </div>

        <div class="checkbox-group">
          <label>
            <input type="checkbox" name="is_penalty_on_moveout" {{ $landboard->is_penalty_on_moveout ? 'checked' : '' }}>
            Penalti Jika Keluar Sebelum Masa Sewa Habis
          </label>
        </div>

        <div class="form-group">
          <label>Nominal Penalti Keluar Sebelum Waktu (Rp)</label>
          <input type="number" name="moveout_penalty_amount" value="{{ old('moveout_penalty_amount', $landboard->moveout_penalty_amount) }}">
        </div>

        <div class="checkbox-group">
          <label>
            <input type="checkbox" name="is_penalty_on_room_change" {{ $landboard->is_penalty_on_room_change ? 'checked' : '' }}>
            Penalti Jika Pindah Kamar
          </label>
        </div>

        <div class="form-group">
          <label>Nominal Penalti Pindah Kamar (Rp)</label>
          <input type="number" name="room_change_penalty_amount" value="{{ old('room_change_penalty_amount', $landboard->room_change_penalty_amount) }}">
        </div>

        <div class="form-group">
          <label>Tampilkan Tombol Perpanjangan Sewa X Hari Sebelum Habis</label>
          <input type="number" name="decision_days_before_end" value="{{ old('decision_days_before_end', $landboard->decision_days_before_end) }}">
        </div>

        <button type="submit">Simpan Pengaturan</button>
      </form>
    </div>
  </div>

</body>
</html>
