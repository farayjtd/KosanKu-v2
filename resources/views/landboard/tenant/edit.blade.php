<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Tenant</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;500&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="/style/font.css">
  @vite('resources/css/app.css')
  <style>
    .bi {
      font-size: 14px;
    }
  </style>
</head>
<body class="use-poppins-normal bg-cover bg-no-repeat bg-center min-h-screen" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
  @include('components.sidebar-landboard')
    <div id="main-content" class="main-content p-6 md:pt-4 w-full">
      <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
        <p><strong class="use-poppins">Edit Data Penghuni</strong></p>
        <p class="text-[14px]">Berikut merupakan data dari akun dengan username <strong>{{ $tenant->account->username }}</strong>. Anda dapat melakukan perubahan.</p>
      </div>
      <div class="mt-6 max-w-full mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-white rounded-b-2xl shadow-xl p-8">
          @if ($errors->any())
            <ul class=" list-disc text-red-600 pl-5">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          @endif

          <form action="{{ route('landboard.tenants.update', $tenant->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid md:grid-cols-2 gap-4 mb-6">

              <div class="">
                <label class="block text-sm font-medium text-gray-600 mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username', $tenant->account->username) }}" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0" required>
              </div>
              
              <div class="">
                <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $tenant->account->email) }}" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Password Baru</label>
                <div class="relative">
                  <input placeholder="Kosongkan jika tidak diganti" id="password" type="password" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-[#31c594]">
                  <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-[#31c594] transition-colors">
                    <i class="bi bi-eye-slash" id="eyeIcon"></i>
                  </button>
                </div>
              </div>

              <!-- Confirm Password Field dengan Icon Mata yang Responsif -->
              <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Konfirmasi Password Baru</label>
                <div class="relative">
                  <input id="confirmpassword" type="password" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-[#31c594]">
                  <button type="button" id="toggleConfirmPassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-[#31c594] transition-colors">
                    <i class="bi bi-eye-slash" id="eyeIcon-conf"></i>
                  </button>
                </div>
              </div>

              <div class="">
                <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $tenant->name) }}" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0" required>
              </div>

              <div class="">
                <label class="block text-sm font-medium text-gray-600 mb-1">No HP</label>
                <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0" required>
              </div>

              <div class="">
                <label class="block text-sm font-medium text-gray-600 mb-1">Alamat</label>
                <input type="text" name="address" value="{{ old('address', $tenant->address) }}" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0" required>
              </div>

              <div class="">
                <label class="block text-sm font-medium text-gray-600 mb-1">Jenis Kelamin</label>
                <select name="gender" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                  <option value="">Pilih</option>
                  <option value="male" {{ old('gender', $tenant->gender) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                  <option value="female" {{ old('gender', $tenant->gender) === 'female' ? 'selected' : '' }}>Perempuan</option>
                </select>
              </div>

              <div class="">
                <label class="block text-sm font-medium text-gray-600 mb-1">Pekerjaan</label>
                <input type="text" name="activity_type" value="{{ old('activity_type', $tenant->activity_type) }}" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
              </div>

              <div class="">
                <label class="block text-sm font-medium text-gray-600 mb-1">Institusi</label>
                <input type="text" name="institution_name" value="{{ old('institution_name', $tenant->institution_name) }}" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
              </div>

              <div class="">
                <label class="block text-sm font-medium text-gray-600 mb-1">Nama Bank</label>
                <input type="text" name="bank_name" value="{{ old('bank_name', $tenant->account->bank_name) }}" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0" required>
              </div>

              <div class="">
                <label class="block text-sm font-medium text-gray-600 mb-1">No Rekening</label>
                <input type="text" name="bank_account" value="{{ old('bank_account', $tenant->account->bank_account) }}" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0" required>
              </div>
            </div>
            <div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-600 mb-1">Foto Avatar</label>
                <div class="rounded-lg bg-gray-50" id="photo-container">
                  <div class="flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                    <input type="file" name="avatar_photo" 
                          class="text-gray-600 flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]" 
                          accept="image/*" 
                          >
                    </button>
                  </div>
                </div>
                @if($tenant->account->avatar)
                  <img src="{{ asset('storage/' . $tenant->account->avatar) }}" alt="Avatar" class="ratio-16x9 h-30 object-cover rounded border-1 transition">
                @endif
              </div>
              <div class="mb-6">
                <label class="block text-sm font-medium text-gray-600 mb-1">Foto Identitas</label>
                <div class="rounded-lg bg-gray-50" id="photo-container">
                  <div class="flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                    <input type="file" name="identity_photo" 
                          class="text-gray-600 flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]" 
                          accept="image/*">
                  </div>
                </div>
              @if($tenant->identity_photo)
                <img src="{{ asset('storage/' . $tenant->identity_photo) }}" alt="Identitas" class="ratio-16x9 h-30 object-cover rounded border-1 transition">
              @endif
              </div>

              <div class="mb-6">
                <label class="block text-sm font-medium text-gray-600 mb-1">Foto Selfie</label>
                <div class="rounded-lg bg-gray-50" id="photo-container">
                  <div class="flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                    <input type="file" name="selfie_photo" 
                          class="text-gray-600 flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]" 
                          accept="image/*">
                  </div>
                </div>
                @if($tenant->selfie_photo)
                  <img src="{{ asset('storage/' . $tenant->selfie_photo) }}" alt="Selfie" class="ratio-16x9 h-30 object-cover rounded border-1 transition">
                @endif
              </div>
            </div>
            <div class="mt-6"></div>
              <button type="submit" class="w-full bg-[#31c594] text-white px-8 py-4 rounded-lg text-base font-semibold transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-[#31c594]/30"><i class="bi bi-save mr-2"></i>Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
    <script>
      const togglePassword = document.getElementById("togglePassword");
      const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");

      const passwordInput = document.getElementById("password");
      const confirmpasswordInput = document.getElementById("confirmpassword");

      const eyeIcon = document.getElementById("eyeIcon");
      const eyeIconConf = document.getElementById("eyeIcon-conf");

      togglePassword.addEventListener("click", function () {
        const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);
        eyeIcon.classList.toggle("bi-eye");
        eyeIcon.classList.toggle("bi-eye-slash");
      });

      toggleConfirmPassword.addEventListener("click", function () {
        const type = confirmpasswordInput.getAttribute("type") === "password" ? "text" : "password";
        confirmpasswordInput.setAttribute("type", type);
        eyeIconConf.classList.toggle("bi-eye");
        eyeIconConf.classList.toggle("bi-eye-slash");
      });
    </script>
</body>
</html>
