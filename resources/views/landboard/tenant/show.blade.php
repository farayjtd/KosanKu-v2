<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detail Tenant</title>
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

    .card {
      background: #fffaf6;
      padding: 25px;
      border-radius: 12px;
      max-width: 800px;
      margin: auto;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    h2 {
      text-align: center;
      color: #5a4430;
      margin-bottom: 25px;
    }

    .avatar {
      display: block;
      margin: 0 auto 20px;
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
      border: 2px solid #cfc4b5;
    }

    .detail {
      margin-bottom: 14px;
      display: flex;
      flex-wrap: wrap;
    }

    .label {
      width: 180px;
      font-weight: 600;
      color: #6b4e3d;
    }

    .value {
      flex: 1;
      color: #3d3d3d;
    }

    .image-section {
      margin-top: 20px;
    }

    .image-section img {
      width: 200px;
      border-radius: 8px;
      border: 1px solid #ccc;
      margin-top: 6px;
    }

    .back-btn {
      display: inline-block;
      margin-top: 30px;
      background: #8d735b;
      color: white;
      padding: 10px 18px;
      border-radius: 8px;
      text-decoration: none;
      transition: background 0.2s ease;
    }

    .back-btn:hover {
      background: #6e5947;
    }

    @media (max-width: 768px) {
      .label {
        width: 100%;
        margin-bottom: 4px;
      }

      .detail {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

  @include('components.sidebar-landboard')

  <div class="main-content">
    <div class="card">
      <h2>Detail Tenant</h2>

      <img class="avatar" src="{{ $tenant->account->avatar ? asset('storage/' . $tenant->account->avatar) : asset('default-avatar.png') }}" alt="Avatar">

      <div class="detail"><span class="label">Username:</span><span class="value">{{ $tenant->account->username }}</span></div>
      <div class="detail"><span class="label">Email:</span><span class="value">{{ $tenant->account->email ?? '-' }}</span></div>
      <div class="detail"><span class="label">Nama:</span><span class="value">{{ $tenant->name }}</span></div>
      <div class="detail"><span class="label">Telepon:</span><span class="value">{{ $tenant->phone }}</span></div>
      @if($tenant->phone)
        <div class="detail">
          <span class="label">Hubungi WA:</span>
          <span class="value">
            <a 
              href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->phone) }}" 
              target="_blank" 
              style="color: #25D366; text-decoration: none;"
            >
              Chat WhatsApp
            </a>
          </span>
        </div>
      @endif
      <div class="detail"><span class="label">Alamat:</span><span class="value">{{ $tenant->address }}</span></div>
      <div class="detail"><span class="label">Jenis Kelamin:</span><span class="value">{{ ucfirst($tenant->gender) }}</span></div>
      <div class="detail"><span class="label">Aktivitas:</span><span class="value">{{ $tenant->activity_type ?: '-' }}</span></div>
      <div class="detail"><span class="label">Institusi:</span><span class="value">{{ $tenant->institution_name ?: '-' }}</span></div>
      <div class="detail"><span class="label">Bank:</span><span class="value">{{ $tenant->account->bank_name ?? '-' }}</span></div>
      <div class="detail"><span class="label">No. Rekening:</span><span class="value">{{ $tenant->account->bank_account ?? '-' }}</span></div>


      @if($tenant->identity_photo)
        <div class="image-section">
          <span class="label">Foto Identitas:</span><br>
          <img src="{{ asset('storage/' . $tenant->identity_photo) }}" alt="Foto Identitas">
        </div>
      @endif

      @if($tenant->selfie_photo)
        <div class="image-section">
          <span class="label">Foto Selfie:</span><br>
          <img src="{{ asset('storage/' . $tenant->selfie_photo) }}" alt="Foto Selfie">
        </div>
      @endif

      <a href="{{ route('landboard.tenants.index') }}" class="back-btn">Kembali</a>
    </div>
  </div>

</body>
</html>
