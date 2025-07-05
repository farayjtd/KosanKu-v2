<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lengkapi Profil</title>
  <style>
    body {
      font-family: sans-serif;
      background: #f1f5f9;
      margin: 0;
      padding: 40px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h1 {
      margin-bottom: 24px;
      color: #1e293b;
    }

    form {
      background: #ffffff;
      padding: 24px;
      border-radius: 12px;
      width: 100%;
      max-width: 540px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.06);
      margin-bottom: 24px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: #334155;
      font-size: 14px;
    }

    input, select {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 8px;
      font-size: 14px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      box-sizing: border-box;
      background: #f9fafb;
      color: #1e293b;
    }

    input[type="file"] {
      padding: 8px;
      background: #ffffff;
    }

    .error-message {
      color: #dc2626;
      font-size: 13px;
      margin-top: 2px;
      margin-bottom: 12px;
    }

    .alert-error {
      color: #dc2626;
      background: #fee2e2;
      padding: 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      max-width: 540px;
    }

    .alert-error ul {
      margin: 0;
      padding-left: 18px;
    }

    button {
      background: #3b82f6;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-size: 15px;
      width: 100%;
      cursor: pointer;
      font-weight: 600;
      transition: background 0.2s ease-in-out;
      margin-top: 16px;
    }

    button:hover {
      background: #2563eb;
    }

    .logout {
      max-width: 540px;
      width: 100%;
    }

    .logout button {
      background: #ef4444;
    }

    .logout button:hover {
      background: #b91c1c;
    }

    @media (max-width: 600px) {
      form, .logout {
        padding: 20px;
        border-radius: 10px;
      }
    }
  </style>
</head>
<body>

  <h1>Lengkapi Profil Anda</h1>

  {{-- Error Global --}}
  @if ($errors->any())
    <div class="alert-error">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Form Lengkapi Profil --}}
  <form action="{{ route('tenant.profile.complete.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label for="name">Nama Lengkap</label>
    <input type="text" name="name" id="name" value="{{ old('name') }}" required>
    @error('name') <div class="error-message">{{ $message }}</div> @enderror

    <label for="email">Email</label>
    <input type="email" name="email" id="email" value="{{ old('email') }}">
    @error('email') <div class="error-message">{{ $message }}</div> @enderror

    <label for="phone">Nomor HP Utama</label>
    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required>
    @error('phone') <div class="error-message">{{ $message }}</div> @enderror

    <label for="avatar">Foto Profil (Avatar)</label>
    <input type="file" name="avatar" id="avatar" accept="image/*">
    @error('avatar') <div class="error-message">{{ $message }}</div> @enderror

    <label for="identity_photo">Foto KTP / Identitas</label>
    <input type="file" name="identity_photo" id="identity_photo" accept="image/*">
    @error('identity_photo') <div class="error-message">{{ $message }}</div> @enderror

    <label for="selfie_photo">Foto Diri (Selfie dengan KTP)</label>
    <input type="file" name="selfie_photo" id="selfie_photo" accept="image/*">
    @error('selfie_photo') <div class="error-message">{{ $message }}</div> @enderror

    <label for="address">Alamat Asal</label>
    <input type="text" name="address" id="address" value="{{ old('address') }}" required>
    @error('address') <div class="error-message">{{ $message }}</div> @enderror

    <label for="gender">Jenis Kelamin</label>
    <select name="gender" id="gender" required>
      <option value="">-- Pilih Jenis Kelamin --</option>
      <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
      <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
    </select>
    @error('gender') <div class="error-message">{{ $message }}</div> @enderror

    <label for="activity_type">Jenis Aktivitas</label>
    <input type="text" name="activity_type" id="activity_type" value="{{ old('activity_type') }}" placeholder="Contoh: Mahasiswa, Pegawai">
    @error('activity_type') <div class="error-message">{{ $message }}</div> @enderror

    <label for="institution_name">Nama Institusi</label>
    <input type="text" name="institution_name" id="institution_name" value="{{ old('institution_name') }}">
    @error('institution_name') <div class="error-message">{{ $message }}</div> @enderror

    <label for="bank_name">Nama Bank</label>
    <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name') }}" required>
    @error('bank_name') <div class="error-message">{{ $message }}</div> @enderror

    <label for="bank_account">Nomor Rekening</label>
    <input type="text" name="bank_account" id="bank_account" value="{{ old('bank_account') }}" required>
    @error('bank_account') <div class="error-message">{{ $message }}</div> @enderror

    <button type="submit">Simpan Profil</button>
  </form>

  {{-- Tombol Logout --}}
  <form action="{{ route('logout') }}" method="POST" class="logout">
    @csrf
    <button type="submit">Logout</button>
  </form>

</body>
</html>
