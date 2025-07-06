<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tambah Kamar - KosanKu</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  <script src="{{ asset('js/sidebar.js') }}" defer></script>
  @vite('resources/css/app.css')
  <style>
    /* Sidebar Styles */
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
    }
  </style>
</head>
<body class="bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    {{-- Sidebar --}}
    @include('components.sidebar-landboard')

    <div id="main-content" class="main-content p-6">
      {{-- Pesan Sukses --}}
      @if(session('success'))
        <div class="max-w-4xl mx-auto mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 font-medium">
          <i class="bi bi-check-circle mr-2"></i>
          {{ session('success') }}
        </div>
      @endif

      {{-- Validasi Error --}}
      @if($errors->any())
        <div class="max-w-4xl mx-auto mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
          <ul class="list-none space-y-1">
            @foreach($errors->all() as $error)
              <li class="text-red-700 font-medium">
                <i class="bi bi-exclamation-triangle mr-2"></i>{{ $error }}
              </li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-[#31c594] to-[#2ba882] text-white p-6 text-center">
          <h2 class="text-xl font-semibold">
            <i class="bi bi-plus-circle mr-2"></i>Tambah Kamar Baru
          </h2>
        </div>

        <div class="p-8">
          <form action="{{ route('landboard.rooms.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                  <i class="bi bi-house mr-2"></i>Tipe Kamar
                </label>
                <input type="text" name="type" 
                       class="w-full px-3 py-3 border-2 border-gray-200 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20" 
                       value="{{ old('type') }}" 
                       placeholder="Contoh: Kamar Standard, Kamar Premium" 
                       required>
              </div>

              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                  <i class="bi bi-hash mr-2"></i>Jumlah Kamar
                </label>
                <input type="number" name="room_quantity" 
                       class="w-full px-3 py-3 border-2 border-gray-200 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20" 
                       min="1" 
                       value="{{ old('room_quantity', 1) }}" 
                       placeholder="Masukkan jumlah kamar" 
                       required>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                  <i class="bi bi-currency-dollar mr-2"></i>Harga per Bulan
                </label>
                <input type="number" name="price" 
                       class="w-full px-3 py-3 border-2 border-gray-200 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20" 
                       value="{{ old('price') }}" 
                       placeholder="Contoh: 500000" 
                       required>
              </div>

              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                  <i class="bi bi-people mr-2"></i>Jenis Kelamin yang Diizinkan
                </label>
                <select name="gender_type" 
                        class="w-full px-3 py-3 border-2 border-gray-200 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20" 
                        required>
                  <option value="">Pilih jenis kelamin</option>
                  <option value="mixed" {{ old('gender_type') == 'mixed' ? 'selected' : '' }}>Campuran</option>
                  <option value="male" {{ old('gender_type') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                  <option value="female" {{ old('gender_type') == 'female' ? 'selected' : '' }}>Perempuan</option>
                </select>
              </div>
            </div>

            {{-- Fasilitas --}}
            <div class="mb-6">
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="bi bi-gear mr-2"></i>Fasilitas Kamar
              </label>
              <div class="rounded-lg bg-gray-50" id="facility-container">
                <div class="flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                  <input type="text" name="facilities[]" 
                         class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20" 
                         placeholder="Contoh: AC, WiFi, Lemari" 
                         required>
                  <button type="button" 
                          class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-1" 
                          onclick="removeField(this)">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
              <button type="button" 
                      class="mt-2 bg-[#31c594] hover:bg-[#2ba882] text-white px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2" 
                      onclick="addField('facility-container', 'facilities[]')">
                <i class="bi bi-plus"></i>Tambah Fasilitas
              </button>
            </div>

            {{-- Aturan --}}
            <div class="mb-6">
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="bi bi-list-ul mr-2"></i>Aturan Kamar
              </label>
              <div class="rounded-lg bg-gray-50" id="rule-container">
                <div class="flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                  <input type="text" name="rules[]" 
                         class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20" 
                         placeholder="Contoh: Tidak boleh merokok, Jam malam 22:00" 
                         required>
                  <button type="button" 
                          class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-1" 
                          onclick="removeField(this)">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
              <button type="button" 
                      class="mt-2 bg-[#31c594] hover:bg-[#2ba882] text-white px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2" 
                      onclick="addField('rule-container', 'rules[]')">
                <i class="bi bi-plus"></i>Tambah Aturan
              </button>
            </div>

            {{-- Foto Kamar --}}
            <div class="mb-8">
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="bi bi-camera mr-2"></i>Foto Kamar
              </label>
              <div class="rounded-lg bg-gray-50" id="photo-container">
                <div class="flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                  <input type="file" name="photos[]" 
                         class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]" 
                         accept="image/*" 
                         required>
                  <button type="button" 
                          class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-1" 
                          onclick="removeField(this)">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
              <button type="button" 
                      class="mt-2 bg-[#31c594] hover:bg-[#2ba882] text-white px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2" 
                      onclick="addField('photo-container', 'photos[]', 'file')">
                <i class="bi bi-plus"></i>Tambah Foto
              </button>
            </div>

            <button type="submit" 
                    class="w-full bg-[#31c594] text-white px-8 py-4 rounded-lg text-base font-semibold transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-[#31c594]/30">
              <i class="bi bi-save mr-2"></i>Simpan Kamar
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Sidebar functionality
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

    // Form functionality
    function removeField(button) {
      const group = button.closest('.flex');
      const container = group.parentElement;
      if (container.querySelectorAll('.flex').length > 1) {
        group.remove();
      } else {
        alert('Minimal 1 input harus ada.');
      }
    }

    function addField(containerId, inputName, type = 'text') {
      const container = document.getElementById(containerId);
      const div = document.createElement('div');
      div.className = 'flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center';
      
      const placeholder = getPlaceholder(inputName);
      const inputClass = 'flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20';
      const fileInputClass = inputClass + ' file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]';
      
      div.innerHTML = `
        <input type="${type}" name="${inputName}" 
               class="${type === 'file' ? fileInputClass : inputClass}" 
               ${type === 'file' ? 'accept="image/*"' : `placeholder="${placeholder}"`} required>
        <button type="button" 
                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-1" 
                onclick="removeField(this)">
          <i class="bi bi-trash"></i>
        </button>`;
      container.appendChild(div);
    }

    function getPlaceholder(inputName) {
      if (inputName.includes('facilities')) {
        return 'Contoh: Kasur, Meja Belajar, Kamar Mandi Dalam';
      } else if (inputName.includes('rules')) {
        return 'Contoh: Dilarang membawa tamu menginap';
      }
      return '';
    }

    function validateForm() {
      const checks = [
        { selector: 'input[name="facilities[]"]', msg: 'Minimal 1 fasilitas harus diisi.' },
        { selector: 'input[name="rules[]"]', msg: 'Minimal 1 aturan harus diisi.' },
        { selector: 'input[name="photos[]"]', msg: 'Minimal 1 foto harus dipilih.' }
      ];

      for (const { selector, msg } of checks) {
        if (![...document.querySelectorAll(selector)].some(input => input.value.trim() !== '')) {
          alert(msg);
          return false;
        }
      }

      return true;
    }
  </script>
</body>
</html>