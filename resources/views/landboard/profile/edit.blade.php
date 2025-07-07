<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profil Landboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  <script src="{{ asset('js/sidebar.js') }}" defer></script>
  @vite('resources/css/app.css')
  <style>
    .sidebar.collapsed {
      width: 80px;
    }
    
    .sidebar.collapsed .menu-text {
      display: none;
    }
    
    .sidebar.collapsed .menu-title {
      display: none;
    }
    
    .sidebar.collapsed .group-title {
      display: none;
    }
    
    .sidebar.collapsed .logo-text {
      display: none;
    }
    
    .sidebar.collapsed .menu-item {
      justify-content: center;
      padding: 0.45rem;
    }
    
    .sidebar.collapsed .profile-btn,
    .sidebar.collapsed .logout-btn {
      padding: 0.45rem;
      justify-content: center;
      min-height: 48px;
    }
    
    .sidebar.collapsed .profile-btn .btn-text,
    .sidebar.collapsed .logout-btn .btn-text {
      display: none;
    }
    
    .sidebar-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      padding: 1rem;
    }
    
    .sidebar-footer {
      margin-top: auto;
      padding: 1rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .main-content {
      margin-left: 240px;
      width: calc(100% - 240px);
      transition: all 0.3s ease;
      min-height: 100vh;
    }
    
    .main-content.collapsed {
      margin-left: 80px;
      width: calc(100% - 80px);
    }
    
    @media (max-width: 768px) {
      .sidebar {
        width: 80px;
      }
      
      .sidebar .menu-text {
        display: none;
      }
      
      .sidebar .menu-title {
        display: none;
      }
      
      .sidebar .group-title {
        display: none;
      }
      
      .sidebar .logo-text {
        display: none;
      }
      
      .sidebar .menu-item {
        justify-content: center;
        padding: 0.75rem;
      }
      
      .sidebar .profile-btn,
      .sidebar .logout-btn {
        padding: 0.75rem;
        justify-content: center;
        min-height: 48px;
      }
      
      .sidebar .profile-btn .btn-text,
      .sidebar .logout-btn .btn-text {
        display: none;
      }
      
      .main-content {
        margin-left: 80px;
        width: calc(100% - 80px);
      }
      
      .main-content.collapsed {
        margin-left: 80px;
        width: calc(100% - 80px);
      }
    }
    
    @media (max-width: 640px) {
      .sidebar {
        width: 60px;
      }
      
      .sidebar .menu-item {
        padding: 0.5rem;
      }
      
      .sidebar .profile-btn,
      .sidebar .logout-btn {
        padding: 0.5rem;
        min-height: 40px;
      }
      
      .main-content {
        margin-left: 60px;
        width: calc(100% - 60px);
      }
      
      .main-content.collapsed {
        margin-left: 60px;
        width: calc(100% - 60px);
      }
      
      @media (max-width: 768px) {
        .sidebar.mobile-expanded {
          width: 100vw !important;
          z-index: 60;
        }
        
        .sidebar.mobile-expanded .menu-text,
        .sidebar.mobile-expanded .menu-title,
        .sidebar.mobile-expanded .group-title,
        .sidebar.mobile-expanded .logo-text,
        .sidebar.mobile-expanded .btn-text {
          display: block !important;
        }
        
        .sidebar.mobile-expanded .menu-item {
          justify-content: flex-start !important;
          padding: 0.5rem 1rem !important;
        }
        
        .sidebar.mobile-expanded .profile-btn,
        .sidebar.mobile-expanded .logout-btn {
          justify-content: center !important;
          padding: 0.5rem 1rem !important;
        }
        
        .mobile-overlay {
          display: none;
          position: fixed;
          top: 0;
          left: 0;
          width: 100vw;
          height: 100vh;
          background: rgba(0, 0, 0, 0.5);
          z-index: 50;
        }
        
        .mobile-overlay.active {
          display: block;
        }
      }
    }
  </style>
</head>
<body class="bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    {{-- Include the same sidebar as dashboard --}}
    @include('components.sidebar-landboard')
    
    <div id="main-content" class="main-content p-6 md:pt-4">

      {{-- Main Card --}}
      <div class="bg-white rounded-xl shadow-lg p-6 max-w-4xl mx-auto">
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
              <i class="bi bi-person-circle gray-800text-xl mr-2"></i>
              <h3 class="text-lg font-semibold text-gray-800">Informasi Akun</h3>
            </div>
            <div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                  <input type="text" name="username" value="{{ old('username', $account->username) }}" 
                         class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#31c594] focus:border-transparent">
                  @error('username') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                  <input type="email" name="email" value="{{ old('email', $account->email) }}" 
                         class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#31c594] focus:border-transparent">
                  @error('email') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                  <input type="password" name="password" placeholder="Kosongkan jika tidak diganti" 
                         class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#31c594] focus:border-transparent">
                  @error('password') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                  <input type="password" name="password_confirmation" 
                         class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#31c594] focus:border-transparent">
                </div>
              </div>
            </div>
          </div>

          {{-- Personal Information Section --}}
          <div class="mb-8">
            <div class="flex items-center mb-4">
              <i class="bi bi-person-badge text-gray-800 text-xl mr-2"></i>
              <h3 class="text-lg font-semibold text-gray-800">Informasi Pribadi</h3>
            </div>
            <div >
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik Kost</label>
                  <input type="text" name="name" value="{{ old('name', $landboard->name) }}" 
                         class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#31c594] focus:border-transparent">
                  @error('name') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                  <input type="text" name="phone" value="{{ old('phone', $landboard->phone) }}" 
                         class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#31c594] focus:border-transparent">
                  @error('phone') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                  <input type="file" name="avatar" accept="image/*" 
                         class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#31c594] focus:border-transparent">
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
              <i class="bi bi-building text-gray-800 text-xl mr-2"></i>
              <h3 class="text-lg font-semibold text-gray-800">Data Kost</h3>
            </div>
            <div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kost</label>
                  <input type="text" name="kost_name" value="{{ old('kost_name', $landboard->kost_name) }}" 
                         class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#31c594] focus:border-transparent">
                  @error('kost_name') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                  <input type="text" name="province" value="{{ old('province', $landboard->province) }}" 
                         class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#31c594] focus:border-transparent">
                  @error('province') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                  <input type="text" name="city" value="{{ old('city', $landboard->city) }}" 
                         class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#31c594] focus:border-transparent">
                  @error('city') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                  <input type="text" name="postal_code" value="{{ old('postal_code', $landboard->postal_code) }}" 
                         class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#31c594] focus:border-transparent">
                  @error('postal_code') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div class="md:col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                  <input type="text" name="full_address" value="{{ old('full_address', $landboard->full_address) }}" 
                         class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#31c594] focus:border-transparent">
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
              <i class="bi bi-bank text-gray-800 text-xl mr-2"></i>
              <h3 class="text-lg font-semibold text-gray-800">Informasi Bank</h3>
            </div>
            <div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                  <input type="text" name="bank_name" value="{{ old('bank_name', $account->bank_name) }}" 
                         class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#31c594] focus:border-transparent">
                  @error('bank_name') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                  @enderror
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                  <input type="text" name="bank_account" value="{{ old('bank_account', $account->bank_account) }}" 
                         class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#31c594] focus:border-transparent">
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
                    class="px-6 py-2 bg-[#31c594] text-white rounded-lg hover:bg-[#2aa082] transition-colors flex items-center">
              <i class="bi bi-check-lg mr-2"></i>
              Simpan Perubahan
            </button>
          </div>
        </form>
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
  </script>
</body>
</html>