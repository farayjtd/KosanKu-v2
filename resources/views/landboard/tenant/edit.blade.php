<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Tenant</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  @vite('resources/css/app.css')
  <style>
    .bi {
      font-size: 14px;
    }
  </style>
</head>
<body class="bg-cover bg-no-repeat bg-center min-h-screen font-sans" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
  @include('components.sidebar-landboard')
    <div id="main-content" class="main-content p-6 md:pt-4 w-full">
      <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-[#31c594] to-[#2ba882] text-white p-6 text-center">
          <h2 class="text-xl font-semibold">
            <i class="bi bi-pencil-square mr-2"></i>Edit Data Penghuni
          </h2>
        </div>
        <div class="bg-white rounded-b-2xl shadow-xl p-8">
          @if ($errors->any())
            <ul class="mb-4 list-disc text-red-600 pl-5">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          @endif

          <form action="{{ route('landboard.tenants.update', $tenant->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
              <i class="bi bi-person mr-2"></i>
              <label>Username</label>
              <input type="text" name="username" value="{{ old('username', $tenant->account->username) }}" class="w-full mt-1 p-2 border-1 border-gray-200 rounded-lg" required>
            </div>
            
            <div class="mb-4">
              <i class="bi bi-envelope mr-2"></i>
              <label>Email</label>
              <input type="email" name="email" value="{{ old('email', $tenant->account->email) }}" class="w-full mt-1 p-2 border-1 border-gray-200 rounded-lg">
            </div>

            <div class="mb-4">
              <i class="bi bi-lock"></i>
              <label>Password Baru (kosongkan jika tidak diganti)</label>
              <input type="password" name="password" class="w-full mt-1 p-2 border-1 border-gray-200 rounded-lg">
            </div>

            <div class="mb-4">
              <i class="bi bi-lock mr-2"></i>
              <label>Konfirmasi Password Baru</label>
              <input type="password" name="password_confirmation" class="w-full mt-1 p-2 border-1 border-gray-200 rounded-lg">
            </div>

            <div class="mb-4">
              <i class="bi bi-person-vcard mr-2"></i>
              <label>Nama Lengkap</label>
              <input type="text" name="name" value="{{ old('name', $tenant->name) }}" class="w-full mt-1 p-2 border-1 border-gray-200 rounded-lg" required>
            </div>

            <div class="mb-4">
              <i class="bi bi-telephone mr-2"></i>
              <label>No HP</label>
              <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}" class="w-full mt-1 p-2 border-1 border-gray-200 rounded-lg" required>
            </div>

            <div class="mb-4">
              <i class="bi bi-geo-alt mr-2"></i>
              <label>Alamat</label>
              <input type="text" name="address" value="{{ old('address', $tenant->address) }}" class="w-full mt-1 p-2 border-1 border-gray-200 rounded-lg" required>
            </div>

            <div class="mb-4">
              <i class="bi bi-gender-ambiguous mr-2"></i>
              <label>Jenis Kelamin</label>
              <select name="gender" class="w-full mt-1 p-2 border-1 border-gray-200 rounded-lg">
                <option value="">Pilih</option>
                <option value="male" {{ old('gender', $tenant->gender) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                <option value="female" {{ old('gender', $tenant->gender) === 'female' ? 'selected' : '' }}>Perempuan</option>
              </select>
            </div>

            <div class="mb-4">
              <i class="bi bi-backpack mr-2"></i>
              <label>Jenis Aktivitas</label>
              <input type="text" name="activity_type" value="{{ old('activity_type', $tenant->activity_type) }}" class="w-full mt-1 p-2 border-1 border-gray-200 rounded-lg">
            </div>

            <div class="mb-4">
              <i class="bi bi-hospital mr-2"></i>
              <label>Institusi</label>
              <input type="text" name="institution_name" value="{{ old('institution_name', $tenant->institution_name) }}" class="w-full mt-1 p-2 border-1 border-gray-200 rounded-lg">
            </div>

            <div class="mb-4">
              <i class="bi bi-bank mr-2"></i>
              <label>Nama Bank</label>
              <input type="text" name="bank_name" value="{{ old('bank_name', $tenant->bank_name) }}" class="w-full mt-1 p-2 border-1 border-gray-200 rounded-lg" required>
            </div>

            <div class="mb-4">
              <i class="bi bi-123 mr-2"></i>
              <label>No Rekening</label>
              <input type="text" name="bank_account" value="{{ old('bank_account', $tenant->bank_account) }}" class="w-full mt-1 p-2 border-1 border-gray-200 rounded-lg" required>
            </div>

            <div class="mb-4">
              <i class="bi bi-person-circle mr-2"></i>
              <label>Foto Avatar</label>
              <div class="rounded-lg bg-gray-50" id="photo-container">
                <div class="flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                  <input type="file" name="avatar_photo" 
                         class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]" 
                         accept="image/*" 
                         >
                  </button>
                </div>
              </div>
              @if($tenant->account->avatar)
                <img src="{{ asset('storage/' . $tenant->account->avatar) }}" alt="Avatar" class="ratio-16x9 h-30 object-cover rounded border-1 transition">
              @endif
            </div>

            <div class="mb-4">
              <i class="bi bi-person-bounding-box mr-2"></i>
              <label>Foto Identitas</label>
              <div class="rounded-lg bg-gray-50" id="photo-container">
                <div class="flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                  <input type="file" name="identity_photo" 
                         class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]" 
                         accept="image/*" 
                         >
                  </button>
                </div>
              </div>
            @if($tenant->identity_photo)
              <img src="{{ asset('storage/' . $tenant->identity_photo) }}" alt="Identitas" class="ratio-16x9 h-30 object-cover rounded border-1 transition">
            @endif
            </div>

            <div class="mb-4">
              <i class="bi bi-person-badge mr-2"></i>
              <label>Foto Selfie</label>
              <div class="rounded-lg bg-gray-50" id="photo-container">
                <div class="flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                  <input type="file" name="selfie_photo" 
                         class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]" 
                         accept="image/*" 
                         >
                </div>
              </div>
              @if($tenant->selfie_photo)
                <img src="{{ asset('storage/' . $tenant->selfie_photo) }}" alt="Selfie" class="ratio-16x9 h-30 object-cover rounded border-1 transition">
              @endif
            </div>
            <button type="submit" class="w-full bg-[#31c594] text-white px-8 py-4 rounded-lg text-base font-semibold transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-[#31c594]/30"><i class="bi bi-save mr-2"></i>Simpan</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
