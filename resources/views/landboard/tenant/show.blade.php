<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detail Tenant</title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  @vite('resources/css/app.css')
</head>

<body class="use-poppins-normal bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-landboard')

    <div id="main-content" class="main-content p-6 md:pt-4 flex-1 ">
      <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
        <p><strong class="use-poppins">Detail Akun</strong></p>
        <p class="text-[14px]">Berikut merupakan detail dari akun dengan username <strong>{{ $tenant->account->username }}</strong></p>
      </div>
      <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <img 
            class="w-20 h-20 object-cover rounded-full shadow-md" 
            src="{{ $tenant->account->avatar ? asset('storage/' . $tenant->account->avatar) : asset('default-avatar.png') }}" 
            alt="Avatar"
          >
          <div class="ml-4">
            <h3 class="text-2xl font-bold text-gray-800 use-poppins">{{ $tenant->name }}</h3>
            <p class="text-gray-600 use-poppins-normal">{{ $tenant->account->username }}</p>
          </div>
        </div>
      </div>
      
      <div class="p-6 bg-white rounded-xl mt-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-4">
          <h4 class="text-lg font-semibold text-gray-800">Identitas Penghuni</h4>
          <div class="mt-4">
            <div class="grid grid-cols-1 md:grid-cols-2">
              <div class="mt-2 border-b border-gray-100">
                <span class="text-gray-600 font-medium text-sm">Username:</span>
                <span class="text-gray-800 font-semibold text-sm">{{ $tenant->account->username }}</span>
              </div>

              <div class="mt-2 border-b border-gray-100">
                <span class="text-gray-600 font-medium text-sm">Email:</span>
                <span class="text-gray-800 font-semibold text-sm">{{ $tenant->account->email ?? '-' }}</span>
              </div>

              <div class="mt-2 border-b border-gray-100">
                <span class="text-gray-600 font-medium text-sm">Nama:</span>
                <span class="text-gray-800 font-semibold text-sm">{{ $tenant->name }}</span>
              </div>

              <div class="mt-2 border-b border-gray-100">
                <span class="text-gray-600 font-medium text-sm">Telepon:</span>
                <span class="text-gray-800 font-semibold text-sm">{{ $tenant->phone }}</span>
              </div>

              <div class="mt-2 border-b border-gray-100">
                <span class="text-gray-600 font-medium text-sm">Jenis Kelamin:</span>
                <span class="text-gray-800 font-semibold text-sm">{{ ucfirst($tenant->gender) }}</span>
              </div>

              <div class="mt-2 border-b border-gray-100">
                <span class="text-gray-600 font-medium text-sm">Aktivitas:</span>
                <span class="text-gray-800 font-semibold text-sm">{{ $tenant->activity_type ?: '-' }}</span>
              </div>

              <div class="mt-2 border-b border-gray-100 md:col-span-2">
                <span class="text-gray-600 font-medium text-sm">Alamat:</span>
                <span class="text-gray-800 font-semibold text-sm">{{ $tenant->address }}</span>
              </div>

              <div class="mt-2 border-b border-gray-100">
                <span class="text-gray-600 font-medium text-sm">Institusi:</span>
                <span class="text-gray-800 font-semibold text-sm">{{ $tenant->institution_name ?: '-' }}</span>
              </div>

              @if($tenant->phone)
                <div class="mt-2 md:col-span-2">
                  <a 
                    href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->phone) }}" 
                    target="_blank" 
                    class="inline-flex items-center py-2 px-4 mt-1 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors text-sm font-medium"
                  >
                    <i class="bi bi-whatsapp mr-2"></i>
                    Chat WhatsApp
                  </a>
                </div>
              @endif
            </div>
          </div>
        </div>
        
        <div class="mt-6 p-4 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <h4 class="text-lg font-semibold text-gray-800">Informasi Bank</h4>
          <div class="mt-4">
            <div class="use-poppins-normal text-[14px]">
              <span class="text-gray-600">Bank:</span>
              <span class="text-gray-700">{{ $tenant->account->bank_name ?? '-' }}</span>
            </div>
              
            <div class="mt-2 use-poppins-normal text-[14px]">
              <span class="text-gray-600">No. Rekening:</span>
              <span class="text-gray-700">{{ $tenant->account->bank_account ?? '-' }}</span>
            </div>
          </div>
        </div>

        <div class="mt-6 p-4 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
          <h4 class="text-lg font-semibold text-gray-800">Foto</h4>
          <div class="mt-4 flex gap-6">
            @if($tenant->identity_photo)
              <div class="flex items-center gap-3">
                <span class="text-gray-600 font-medium text-sm">Foto Identitas:</span>
                <img 
                  src="{{ asset('storage/' . $tenant->identity_photo) }}" 
                  alt="Foto Identitas" 
                  class="w-32 h-32 object-cover rounded border border-gray-300"
                >
              </div>
            @endif

            @if($tenant->selfie_photo)
              <div class="flex items-center gap-3">
                <span class="text-gray-600 font-medium text-sm">Foto Selfie:</span>
                <img 
                  src="{{ asset('storage/' . $tenant->selfie_photo) }}" 
                  alt="Foto Selfie" 
                  class="w-32 h-32 object-cover rounded border border-gray-300"
                >
              </div>
            @endif
          </div>
        </div>

        <div class="flex justify-start mt-4">
          <a 
            href="{{ route('landboard.tenants.index') }}" 
            class="bg-[#31c594] text-white px-8 py-4 rounded-lg text-base font-semibold transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-[#31c594]/30"
          >
            <i class="bi bi-arrow-left mr-2"></i>
            Kembali
          </a>
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
  </script>
</body>
</html>