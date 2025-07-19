<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profil Landboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;500&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="/style/font.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  @vite('resources/css/app.css')
</head>
<body class="use-poppins-normal bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-landboard')
    
    <div id="main-content" class="main-content p-6 md:pt-4">
      <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
        <p><strong class="use-poppins">Informasi Akun</strong></p>
        <p class="text-[14px]">Ubah data diri, informasi bank, hingga data kos anda.</p>
      </div>
      <div class="bg-white rounded-xl shadow-lg p-6 max-w-full mt-6">
        {{-- Notifications --}}
        @if(session('success'))
          <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
              <i class="bi bi-check-circle-fill mr-2"></i>
              {{ session('success') }}
            </div>
          </div>
        @endif

        @if(session('error'))
          <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
              <i class="bi bi-exclamation-triangle-fill mr-2"></i>
              {{ session('error') }}
            </div>
          </div>
        @endif

        <form action="{{ route('landboard.profile.update') }}" method="POST" enctype="multipart/form-data">
          @csrf

          {{-- Account Section --}}
          <div class="mb-8">
            <div class="flex items-center mb-4">
              <h3 class="text-lg font-semibold text-gray-600">Informasi Akun</h3>
            </div>
            <div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-600 mb-1">Username</label>
                  <input type="text" name="username" value="{{ old('username', $account->username) }}" 
                         class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                  @error('username') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                  <input type="email" name="email" value="{{ old('email', $account->email) }}" 
                         class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                  @error('email') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div class="relative">
                  <label class="block text-sm font-medium text-gray-600 mb-1">Password Baru</label>
                  <input id="password" type="password" name="password" placeholder="Kosongkan jika tidak diganti" 
                         class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                  @error('password') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                  <button type="button" id="togglePassword" class="absolute right-3 top-11.5 -translate-y-1/2 text-gray-500 hover:text-[#31c594]">
                    <i class="bi bi-eye-slash" id="eyeIcon"></i>
                  </button>
                </div>

                <div class="relative">
                  <label class="block text-sm font-medium text-gray-600 mb-1">Konfirmasi Password</label>
                  <input id="confirmpassword" type="password" name="password_confirmation" 
                         class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                         <button type="button" id="toggleConfirmPassword" class="absolute right-3 top-11.5 -translate-y-1/2 text-gray-500 hover:text-[#31c594]">
                           <i class="bi bi-eye-slash" id="eyeIcon-conf"></i>
                         </button>
                </div>
              </div>
            </div>
          </div>

          {{-- Personal Information Section --}}
          <div class="mb-8">
            <div class="flex items-center mb-4">
              <h3 class="text-lg font-semibold text-gray-700">Informasi Pribadi</h3>
            </div>
            <div >
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-600 mb-1">Nama Pemilik Kost</label>
                  <input type="text" name="name" value="{{ old('name', $landboard->name) }}" 
                         class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                  @error('name') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-600 mb-1">No HP</label>
                  <input type="text" name="phone" value="{{ old('phone', $landboard->phone) }}" 
                         class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                  @error('phone') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-600 mb-1">Foto Profil</label>
                  <input type="file" name="avatar" accept="image/*" 
                         class="text-gray-600 flex-1 px-3 w-full py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]">
                  @if($account->avatar)
                    <div class="mt-2">
                      <img src="{{ asset('storage/' . $account->avatar) }}" alt="Avatar" 
                           class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                    </div>
                  @endif
                  @error('avatar') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>
              </div>
            </div>
          </div>

          {{-- Kost Information Section --}}
          <div class="mb-8">
            <div class="flex items-center mb-4">
              <h3 class="text-lg font-semibold text-gray-700">Data Kost</h3>
            </div>
            <div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-600 mb-1">Nama Kost</label>
                  <input type="text" name="kost_name" value="{{ old('kost_name', $landboard->kost_name) }}" 
                         class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                  @error('kost_name') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-600 mb-1">Provinsi</label>
                  <input type="text" name="province" value="{{ old('province', $landboard->province) }}" 
                         class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                  @error('province') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-600 mb-1">Kota</label>
                  <input type="text" name="city" value="{{ old('city', $landboard->city) }}" 
                         class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                  @error('city') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-600 mb-1">Kode Pos</label>
                  <input type="text" name="postal_code" value="{{ old('postal_code', $landboard->postal_code) }}" 
                         class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                  @error('postal_code') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-600 mb-1">Alamat Lengkap</label>
                  <input type="text" name="full_address" value="{{ old('full_address', $landboard->full_address) }}" 
                         class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                  @error('full_address') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>
              </div>
            </div>
          </div>

          {{-- Bank Information Section --}}
          <div class="mb-8">
            <div class="flex items-center mb-4">
              <h3 class="text-lg font-semibold text-gray-700">Informasi Bank</h3>
            </div>
            <div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-600 mb-1">Nama Bank</label>
                  <input type="text" name="bank_name" value="{{ old('bank_name', $account->bank_name) }}" 
                         class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                  @error('bank_name') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-600 mb-1">Nomor Rekening</label>
                  <input type="text" name="bank_account" value="{{ old('bank_account', $account->bank_account) }}" 
                         class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                  @error('bank_account') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>
              </div>
            </div>
          </div>

          {{-- Action Buttons --}}
          <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <button type="submit" 
                    class="items-center justify-center w-full bg-[#31c594] text-white px-8 py-4 rounded-lg text-base font-semibold transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-[#31c594]/30 flex">
              <i class="bi bi-save mr-2"></i>
              Simpan Perubahan
            </button>
          </div>
        </form>
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