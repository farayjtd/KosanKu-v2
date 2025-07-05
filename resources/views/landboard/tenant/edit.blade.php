<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Tenant</title>
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
      max-width: 800px;
      margin: auto;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    h2 {
      text-align: center;
      color: #5a4430;
      margin-bottom: 20px;
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

    .error ul {
      margin: 0;
      padding-left: 18px;
    }

    img {
      margin-top: 10px;
      border-radius: 8px;
      max-width: 100px;
      border: 2px solid #d6ccc2;
    }
  </style>
</head>
<body>

@include('components.sidebar-landboard')

<div class="main-content">
  <div class="card">
    <h2>Edit Tenant</h2>

    @if(session('success'))
      <div class="success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
      <div class="error">
        <ul>
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('landboard.tenants.update', $tenant->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <label>Username</label>
      <input type="text" name="username" value="{{ old('username', $tenant->account->username) }}" required>

      <label>Email</label>
      <input type="email" name="email" value="{{ old('email', $tenant->account->email) }}">

      <label>Password Baru (kosongkan jika tidak diganti)</label>
      <input type="password" name="password">

      <label>Konfirmasi Password Baru</label>
      <input type="password" name="password_confirmation">

      <label>Nama Lengkap</label>
      <input type="text" name="name" value="{{ old('name', $tenant->name) }}" required>

      <label>No HP</label>
      <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}" required>

      <label>Alamat</label>
      <input type="text" name="address" value="{{ old('address', $tenant->address) }}" required>

      <label>Jenis Kelamin</label>
      <select name="gender">
        <option value="">Pilih</option>
        <option value="male" {{ old('gender', $tenant->gender) === 'male' ? 'selected' : '' }}>Laki-laki</option>
        <option value="female" {{ old('gender', $tenant->gender) === 'female' ? 'selected' : '' }}>Perempuan</option>
      </select>

      <label>Jenis Aktivitas</label>
      <input type="text" name="activity_type" value="{{ old('activity_type', $tenant->activity_type) }}">

      <label>Institusi</label>
      <input type="text" name="institution_name" value="{{ old('institution_name', $tenant->institution_name) }}">

      <label>Nama Bank</label>
      <input type="text" name="bank_name" value="{{ old('bank_name', $tenant->bank_name) }}" required>

      <label>No Rekening</label>
      <input type="text" name="bank_account" value="{{ old('bank_account', $tenant->bank_account) }}" required>

      <label>Foto Avatar</label>
      <input type="file" name="avatar" accept="image/*">
      @if($tenant->account->avatar)
        <img src="{{ asset('storage/' . $tenant->account->avatar) }}" alt="Avatar">
      @endif

      <label>Foto Identitas</label>
      <input type="file" name="identity_photo" accept="image/*">
      @if($tenant->identity_photo)
        <img src="{{ asset('storage/' . $tenant->identity_photo) }}" alt="Identitas">
      @endif

      <label>Foto Selfie</label>
      <input type="file" name="selfie_photo" accept="image/*">
      @if($tenant->selfie_photo)
        <img src="{{ asset('storage/' . $tenant->selfie_photo) }}" alt="Selfie">
      @endif

      <button type="submit">Simpan Perubahan</button>
    </form>
  </div>
</div>

</body>
</html>
