<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Tenant</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/style/font.css">
    @vite('resources/css/app.css')
</head>
<body class="use-poppins-normal bg-cover bg-no-repeat bg-center min-h-screen" style="background-image: url('/assets/auth.png')">
    <div id="wrapper" class="flex min-h-screen">
        @include('components.sidebar-tenant')
        <div id="main-content" class="main-content p-6 md:pt-4 w-full">
            <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
                <p><strong class="use-poppins">Edit Profil</strong></p>
                <p class="text-[14px]">Berikut merupakan data profil Anda. Anda dapat melakukan perubahan sesuai kebutuhan.</p>
            </div>
            
            <div class="mt-6 max-w-full mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-white rounded-b-2xl shadow-xl p-8">
                    {{-- Flash Message --}}
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Error List --}}
                    @if($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc text-red-600 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('tenant.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid md:grid-cols-2 gap-4 mb-6">
                            
                            <div class="">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Username</label>
                                <input type="text" name="username" value="{{ old('username', $account->username) }}" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0" required>
                                @error('username') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                                <input type="email" name="email" value="{{ old('email', $account->email) }}" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                                @error('email') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Password Baru</label>
                                <div class="relative">
                                    <input placeholder="Kosongkan jika tidak diganti" id="password" name="password" type="password" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-[#31c594]">
                                    <button type="button" id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-[#31c594] transition-colors">
                                        <i class="bi bi-eye-slash" id="eyeIcon"></i>
                                    </button>
                                </div>
                                @error('password') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Konfirmasi Password</label>
                                <div class="relative">
                                    <input id="confirmpassword" name="password_confirmation" type="password" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-[#31c594]">
                                    <button type="button" id="toggleConfirmPassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-[#31c594] transition-colors">
                                        <i class="bi bi-eye-slash" id="eyeIcon-conf"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $tenant->name) }}" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0" required>
                                @error('name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="">
                                <label class="block text-sm font-medium text-gray-600 mb-1">No HP</label>
                                <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0" required>
                                @error('phone') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Alamat Asal</label>
                                <input type="text" name="address" value="{{ old('address', $tenant->address) }}" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0" required>
                                @error('address') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Jenis Kelamin</label>
                                <select name="gender" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                                    <option value="">-- Pilih --</option>
                                    <option value="male" {{ old('gender', $tenant->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ old('gender', $tenant->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Pekerjaan</label>
                                <input type="text" name="activity_type" value="{{ old('activity_type', $tenant->activity_type) }}" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                                @error('activity_type') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Nama Instansi</label>
                                <input type="text" name="institution_name" value="{{ old('institution_name', $tenant->institution_name) }}" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                                @error('institution_name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Nama Bank</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name', $account->bank_name) }}" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0" required>
                                @error('bank_name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Nomor Rekening</label>
                                <input type="text" name="bank_account" value="{{ old('bank_account', $account->bank_account) }}" class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0" required>
                                @error('bank_account') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div>
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Foto Profil (Avatar)</label>
                                <div class="rounded-lg bg-gray-50" id="photo-container">
                                    <div class="flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                                        <input type="file" name="avatar" 
                                            class="text-gray-600 flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]" 
                                            accept="image/*">
                                    </div>
                                </div>
                                @if ($account->avatar)
                                    <img src="{{ asset('storage/' . $account->avatar) }}" alt="Foto Avatar" class="ratio-16x9 h-30 object-cover rounded border-1 transition">
                                @endif
                                @error('avatar') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Foto Identitas (KTP/ID)</label>
                                <div class="rounded-lg bg-gray-50" id="photo-container">
                                    <div class="flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                                        <input type="file" name="identity_photo" 
                                            class="text-gray-600 flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]" 
                                            accept="image/*">
                                    </div>
                                </div>
                                @if ($tenant->identity_photo)
                                    <img src="{{ asset('storage/' . $tenant->identity_photo) }}" alt="Foto Identitas" class="ratio-16x9 h-30 object-cover rounded border-1 transition">
                                @endif
                                @error('identity_photo') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Foto Diri (Selfie dengan KTP)</label>
                                <div class="rounded-lg bg-gray-50" id="photo-container">
                                    <div class="flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                                        <input type="file" name="selfie_photo" 
                                            class="text-gray-600 flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]" 
                                            accept="image/*">
                                    </div>
                                </div>
                                @if ($tenant->selfie_photo)
                                    <img src="{{ asset('storage/' . $tenant->selfie_photo) }}" alt="Foto Selfie" class="ratio-16x9 h-30 object-cover rounded border-1 transition">
                                @endif
                                @error('selfie_photo') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="w-full bg-[#31c594] text-white px-8 py-4 rounded-lg text-base font-semibold transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-[#31c594]/30">
                                <i class="bi bi-save mr-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
      const sidebar = document.getElementById('sidebar');
      const mainContent = document.getElementById('main-content');
      const toggleBtn = document.getElementById('toggleSidebar');
      
      const overlay = document.createElement('div');
      overlay.className = 'mobile-overlay';
      overlay.id = 'mobile-overlay';
      document.body.appendChild(overlay);

      function initializeSidebar() {
        if (window.innerWidth <= 768) {
          if (sidebar) {
            sidebar.classList.add('collapsed');
            sidebar.classList.remove('mobile-expanded');
          }
          if (mainContent) {
            mainContent.classList.add('collapsed');
          }
          overlay.classList.remove('active');
        } else {
          if (sidebar) {
            sidebar.classList.remove('mobile-expanded');
          }
          overlay.classList.remove('active');
        }
      }

      initializeSidebar();

      if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
          if (window.innerWidth <= 768) {
            if (sidebar.classList.contains('mobile-expanded')) {
              sidebar.classList.remove('mobile-expanded');
              sidebar.classList.add('collapsed');
              overlay.classList.remove('active');
            } else {
              sidebar.classList.remove('collapsed');
              sidebar.classList.add('mobile-expanded');
              overlay.classList.add('active');
            }
          } else {
            sidebar.classList.toggle('collapsed');
            if (mainContent) {
              mainContent.classList.toggle('collapsed');
            }
          }
        });
      }
      
      overlay.addEventListener('click', function() {
        if (window.innerWidth <= 768) {
          sidebar.classList.remove('mobile-expanded');
          sidebar.classList.add('collapsed');
          overlay.classList.remove('active');
        }
      });

      window.addEventListener('resize', function() {
        initializeSidebar();
      });
    });
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