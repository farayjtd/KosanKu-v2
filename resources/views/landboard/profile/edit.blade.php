<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profil Landboard</title>
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
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    h2 {
      margin-bottom: 20px;
      color: #5a4430;
      text-align: center;
    }

    h4 {
      margin-top: 30px;
      margin-bottom: 10px;
      color: #6b4e3d;
      font-size: 18px;
      border-bottom: 1px solid #ddd0c1;
      padding-bottom: 5px;
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
    }

    .text-danger {
      color: #b91c1c;
      font-size: 13px;
      margin-top: 4px;
      display: block;
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

    img {
      margin-top: 10px;
      border-radius: 8px;
      max-width: 100px;
      border: 2px solid #d6ccc2;
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

  {{-- Sidebar Landboard --}}
  @include('components.sidebar-landboard')

  <div class="main-content">
    <div class="card">
      <h2>Edit Profil Landboard</h2>

      {{-- Notifikasi Sukses --}}
      @if(session('success'))
        <div class="success">{{ session('success') }}</div>
      @endif

      {{-- Notifikasi Error --}}
      @if(session('error'))
        <div class="error">{{ session('error') }}</div>
      @endif

      <form action="{{ route('landboard.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Bagian Akun --}}
        <h4>Akun</h4>

        <label>Username</label>
        <input type="text" name="username" value="{{ old('username', $account->username) }}">
        @error('username') <span class="text-danger">{{ $message }}</span> @enderror

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $account->email) }}">
        @error('email') <span class="text-danger">{{ $message }}</span> @enderror

        <label>Password Baru (kosongkan jika tidak diganti)</label>
        <input type="password" name="password">
        @error('password') <span class="text-danger">{{ $message }}</span> @enderror

        <label>Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation">

        <label>Nama Pemilik Kost</label>
        <input type="text" name="name" value="{{ old('name', $landboard->name) }}">
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror

        <label>No HP</label>
        <input type="text" name="phone" value="{{ old('phone', $landboard->phone) }}">
        @error('phone') <span class="text-danger">{{ $message }}</span> @enderror

        <label>Foto Profil</label>
        <input type="file" name="avatar" accept="image/*">
        @if($account->avatar)
          <img src="{{ asset('storage/' . $account->avatar) }}" alt="Avatar">
        @endif
        @error('avatar') <span class="text-danger">{{ $message }}</span> @enderror

        {{-- Bagian Data Kost --}}
        <h4>Data Kost</h4>

        <label>Nama Kost</label>
        <input type="text" name="kost_name" value="{{ old('kost_name', $landboard->kost_name) }}">
        @error('kost_name') <span class="text-danger">{{ $message }}</span> @enderror

        <label>Provinsi</label>
        <input type="text" name="province" value="{{ old('province', $landboard->province) }}">
        @error('province') <span class="text-danger">{{ $message }}</span> @enderror

        <label>Kota</label>
        <input type="text" name="city" value="{{ old('city', $landboard->city) }}">
        @error('city') <span class="text-danger">{{ $message }}</span> @enderror

        <label>Kode Pos</label>
        <input type="text" name="postal_code" value="{{ old('postal_code', $landboard->postal_code) }}">
        @error('postal_code') <span class="text-danger">{{ $message }}</span> @enderror

        <label>Alamat Lengkap</label>
        <input type="text" name="full_address" value="{{ old('full_address', $landboard->full_address) }}">
        @error('full_address') <span class="text-danger">{{ $message }}</span> @enderror

        {{-- Bagian Informasi Bank --}}
        <h4>Informasi Bank</h4>

        <label>Nama Bank</label>
        <input type="text" name="bank_name" value="{{ old('bank_name', $account->bank_name) }}">
        @error('bank_name') <span class="text-danger">{{ $message }}</span> @enderror

        <label>Nomor Rekening</label>
        <input type="text" name="bank_account" value="{{ old('bank_account', $account->bank_account) }}">
        @error('bank_account') <span class="text-danger">{{ $message }}</span> @enderror

        <button type="submit">Simpan Perubahan</button>
      </form>
    </div>
  </div>

</body>
</html>
