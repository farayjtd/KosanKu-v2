<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Kamar</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
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
      border-1-top: 1px solid rgba(255, 255, 255, 0.1);
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
    .photo-wrapper {
      position: relative;
      display: inline-block;
      cursor: pointer;
    }
    .photo-wrapper img {
      transition: 0.3s ease;
    }
    .photo-wrapper:hover .delete-icon {
      opacity: 1;
    }
    .delete-icon {
      position: absolute;
      top: 2.5rem;
      right: 4.6rem;
      padding: 4px;
      border-radius: 50%;
      color: #dc2626;
      font-size: 1.2rem;
      opacity: 0;
      transition: 0.2s ease;
    }
    .photo-selected {
      opacity: 0.5;
      filter: grayscale(100%);
    }
  </style>
</head>
<body class="bg-cover bg-no-repeat bg-center min-h-screen font-sans" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-landboard')

    <div id="main-content" class="main-content p-6 md:pt-4 w-full">
      <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg">
        <div class="bg-gradient-to-r from-[#31c594] to-[#2ba882] rounded-t-2xl text-white p-8 text-center">
          <h1 class="text-3xl font-bold font-poppins mb-2"><i class="bi bi-pencil-square mr-2"></i>Edit Kamar</h1>
        </div>
        <div class="bg-white rounded-b-2xl shadow-xl p-8">
          @if ($errors->any())
            <ul class="mb-4 list-disc text-red-600 pl-5">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          @endif

          <form action="{{ route('landboard.rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            @csrf
            @method('PUT')

            <div class="mb-4">
              <label class="block font-medium">Tipe Kamar</label>
              <input type="text" name="type" value="{{ old('type', $room->type) }}" required class="w-full mt-1 p-2 border-1 border-gray-200 rounded-lg">
            </div>

            <div class="mb-4">
              <label class="block font-medium">Harga per Bulan</label>
              <input type="number" name="price" value="{{ old('price', $room->price) }}" required class="w-full mt-1 p-2 border-1 border-gray-200 rounded-lg">
            </div>

            <div class="mb-4">
              <label class="block font-medium">Jenis Kelamin yang Diizinkan</label>
              <select name="gender_type" required class="w-full mt-1 p-2 border-1 border-gray-200 rounded-lg">
                <option value="male" {{ old('gender_type', $room->gender_type) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                <option value="female" {{ old('gender_type', $room->gender_type) === 'female' ? 'selected' : '' }}>Perempuan</option>
                <option value="mixed" {{ old('gender_type', $room->gender_type) === 'mixed' ? 'selected' : '' }}>Campuran</option>
              </select>
            </div>

            <div class="mb-4">
              <label class="block font-medium">Foto Lama</label>
              <div id="existing-photos" class="flex flex-wrap gap-4 mt-2">
                @foreach ($room->photos as $photo)
                  <div class="photo-wrapper">
                    <img src="{{ asset('storage/' . $photo->path) }}" data-id="{{ $photo->id }}" class="ratio-16x9 h-30 object-cover rounded border-1 transition" onclick="toggleSelectPhoto(this)">
                    <i class="bi bi-trash delete-icon"></i>
                    <input type="checkbox" name="delete_photos[]" value="{{ $photo->id }}" class="hidden">
                  </div>
                @endforeach
              </div>
            </div>

            <div class="mb-4">
              <label class="block font-medium text-gray-800">Tambah Foto Baru</label>
              <div id="photo-container">
                <div class="flex gap-2 mt-2 items-center">
                  <label class="w-full cursor-pointer flex items-center justify-between p-3 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">
                    <span class="file-name truncate text-gray-500">Pilih file...</span>
                    <span class="text-gray-400 font-medium">Browse</span>
                    <input type="file" name="photos[]" accept="image/*" class="hidden" onchange="updateFileName(this)">
                  </label>
                  <button type="button" onclick="removeField(this)" class="text-red-600 hover:text-red-800 text-lg">
                    <i class="bi bi-trash"></i>
                  </button>
                  <button type="button" onclick="addPhoto()" class="text-lg text-black hover:text-[#31c594]">
                    <i class="bi bi-plus-lg"></i>
                  </button>
                </div>
              </div>
              
            </div>
            
            <div class="mb-4">
              <label class="block font-medium">Fasilitas</label>
              <div id="facility-container">
                @forelse ($room->facilities as $facility)
                  <div class="flex gap-2 mt-2 items-center">
                    <input type="text" name="facilities[]" value="{{ $facility->name }}" class="w-full p-3 border border-gray-200 rounded-md">
                    <button type="button" onclick="removeField(this)" class="text-red-600 hover:text-red-800 text-lg">
                      <i class="bi bi-trash"></i>
                    </button>
                    <button type="button" onclick="addFacility()" class="text-black hover:text-[#31c594] text-lg">
                      <i class="bi bi-plus-lg"></i>
                    </button>
                  </div>
                @empty
                  <div class="flex gap-2 mt-2 items-center">
                    <input type="text" name="facilities[]" class="w-full p-3 border border-gray-200 rounded-md">
                    <button type="button" onclick="removeField(this)" class="text-red-600 hover:text-red-800 text-lg">
                      <i class="bi bi-trash"></i>
                    </button>
                    <button type="button" onclick="addFacility()" class="text-black hover:text-[#31c594] text-lg">
                      <i class="bi bi-plus-lg"></i>
                    </button>
                  </div>
                @endforelse
              </div>
            </div>

            <div class="mb-4">
              <label class="block font-medium">Aturan</label>
              <div id="rule-container">
                @forelse ($room->rules as $rule)
                  <div class="flex gap-2 mt-2 items-center">
                    <input type="text" name="rules[]" value="{{ $rule->name }}" class="w-full p-3 border border-gray-200 rounded-md">
                    <button type="button" onclick="removeField(this)" class="text-red-600 hover:text-red-800 text-lg">
                      <i class="bi bi-trash"></i>
                    </button>
                    <button type="button" onclick="addRule()" class="text-black hover:text-[#31c594] text-lg">
                      <i class="bi bi-plus-lg"></i>
                    </button>
                  </div>
                @empty
                  <div class="flex gap-2 mt-2 items-center">
                    <input type="text" name="rules[]" class="w-full p-3 border border-gray-200 rounded-md">
                    <button type="button" onclick="removeField(this)" class="text-red-600 hover:text-red-800 text-lg">
                      <i class="bi bi-trash"></i>
                    </button>
                    <button type="button" onclick="addRule()" class="text-black hover:text-[#31c594] text-lg">
                      <i class="bi bi-plus-lg"></i>
                    </button>
                  </div>
                @endforelse
              </div>
            </div>


            <div class="mb-6">
              <label class="block font-medium mb-2">Perbarui Untuk</label>
              <div class="space-y-2">
                <label class="inline-flex items-center">
                  <input type="radio" name="apply_all" value="0" checked class="text-green-600">
                  <span class="ml-2">Hanya kamar ini</span>
                </label><br>
                <label class="inline-flex items-center">
                  <input type="radio" name="apply_all" value="1" class="text-green-600">
                  <span class="ml-2">Semua kamar tipe ini ({{ $room->type }})</span>
                </label>
              </div>
            </div>
            <button type="submit" class="w-full py-3 bg-[#2ba882] text-white rounded-md transition">Simpan Perubahan</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    function addPhoto () {
      const container = document.getElementById('photo-container');

      const div = document.createElement('div');
      div.className = 'flex gap-2 mt-2 items-center';

      div.innerHTML = `
        <label class="w-full cursor-pointer flex items-center justify-between p-3 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">
          <span class="file-name truncate text-gray-500">Pilih file...</span>
          <span class="text-gray-400 font-medium">Browse</span>
          <input type="file" name="photos[]" accept="image/*" class="hidden"
                onchange="updateFileName(this)">
        </label>

        <button type="button"
                class="text-red-600 hover:text-red-800 text-lg"
                onclick="removeField(this)">
          <i class="bi bi-trash"></i>
        </button>

        <button type="button"
                class="text-lg text-black hover:text-[#31c594]"
                onclick="addPhoto()">
          <i class="bi bi-plus-lg"></i>
        </button>
      `;

      container.appendChild(div);
    }

    function removeField(btn) {
      const row = btn.closest('.flex');
      const container = row.parentElement;
      if (container.querySelectorAll('.flex').length > 1) {
        row.remove();
      } else {
        alert('Minimal 1 foto harus ada.');
      }
    }

    function updateFileName(input) {
      const span = input.closest('label').querySelector('.file-name');
      span.textContent = input.files.length ? input.files[0].name : 'Pilih file...';
    }

    function addFacility() {
      const container = document.getElementById('facility-container');
      const div = document.createElement('div');
      div.className = 'flex gap-2 mt-2 items-center';
      div.innerHTML = `
        <input type="text" name="facilities[]" class="w-full p-3 border border-gray-200 rounded-md">
        <button type="button" onclick="removeField(this)" class="text-red-600 hover:text-red-800 text-lg">
          <i class="bi bi-trash"></i>
        </button>
        <button type="button" onclick="addFacility()" class="text-black hover:text-[#31c594] text-lg">
          <i class="bi bi-plus-lg"></i>
        </button>
      `;
      container.appendChild(div);
    }

    function addRule() {
      const container = document.getElementById('rule-container');
      const div = document.createElement('div');
      div.className = 'flex gap-2 mt-2 items-center';
      div.innerHTML = `
        <input type="text" name="rules[]" class="w-full p-3 border border-gray-200 rounded-md">
        <button type="button" onclick="removeField(this)" class="text-red-600 hover:text-red-800 text-lg">
          <i class="bi bi-trash"></i>
        </button>
        <button type="button" onclick="addRule()" class="text-black hover:text-[#31c594] text-lg">
          <i class="bi bi-plus-lg"></i>
        </button>
      `;
      container.appendChild(div);
    }

    function removeField(btn) {
      const parent = btn.closest('.flex');
      const container = parent.parentElement;
      if (container.querySelectorAll('.flex').length > 1) {
        parent.remove();
      } else {
        alert('Minimal satu kolom harus tetap ada.');
      }
    }

    function validateForm() {
      const facilityInputs = document.querySelectorAll('input[name="facilities[]"]');
      const ruleInputs = document.querySelectorAll('input[name="rules[]"]');
      const photoInputs = document.querySelectorAll('input[name="photos[]"]');
      const existingChecked = document.querySelectorAll('input[name="delete_photos[]"]:checked');
      const totalOldPhotos = document.querySelectorAll('#existing-photos input[type="checkbox"]').length;
      const hasOldPhotoRemaining = totalOldPhotos > existingChecked.length;

      const hasFacility = [...facilityInputs].some(input => input.value.trim() !== '');
      const hasRule = [...ruleInputs].some(input => input.value.trim() !== '');
      const hasNewPhoto = [...photoInputs].some(input => input.value !== '');

      if (!hasFacility) {
        alert("Minimal 1 fasilitas harus diisi.");
        return false;
      }

      if (!hasRule) {
        alert("Minimal 1 aturan harus diisi.");
        return false;
      }

      if (!hasNewPhoto && !hasOldPhotoRemaining) {
        alert("Minimal harus ada 1 foto (lama atau baru).");
        return false;
      }

      return true;
    }
    function toggleSelectPhoto(img) {
      const wrapper = img.closest('.photo-wrapper');
      const checkbox = wrapper.querySelector('input[type="checkbox"]');
      const selected = img.classList.toggle('photo-selected');
      checkbox.checked = selected;
    }
  </script>
</body>
</html>
