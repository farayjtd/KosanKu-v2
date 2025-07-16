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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  @vite('resources/css/app.css')
  <style>
        .photo-warning {
      background-color: #fef3c7;
      border: 1px solid #f59e0b;
      color: #92400e;
      padding: 0.75rem;
      border-radius: 0.5rem;
      margin-bottom: 1rem;
      font-size: 0.875rem;
      display: none;
    }

    .photo-warning.show {
      display: block;
    }
    .photo-wrapper {
      position: relative;
      display: inline-block;
      cursor: pointer;
      width: 200px;
      height: 112.5px; /* 16:9 ratio for 200px width */
      border-radius: 8px;
      overflow: hidden;
      border: 1px solid #e5e7eb;
    }
    
    .photo-wrapper img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
      transition: 0.3s ease;
      border-radius: 0;
    }
    
    .photo-wrapper:hover .delete-icon {
      opacity: 1;
    }
    
    .delete-icon {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: white;
      font-size: 1.2rem;
      opacity: 0;
      transition: 0.2s ease;
      backdrop-filter: blur(4px);
      z-index: 10;
    }
    
    .delete-icon:hover {
      transform: translate(-50%, -50%) scale(1.1);
    }
    
    .photo-selected {
      opacity: 0.5;
      filter: grayscale(100%);
    }
    
    .photo-selected::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.3);
      border-radius: 8px;
    }
    
    .action-button-hidden {
      display: none !important;
    }
    
    .photo-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 1rem;
      margin-top: 0.5rem;
    }
    
    @media (max-width: 640px) {
      .photo-wrapper {
        width: 160px;
        height: 90px; /* 16:9 ratio for 160px width */
      }
      
      .photo-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 0.75rem;
      }
    }
  </style>
</head>
<body class="use-poppins-normal bg-cover bg-no-repeat bg-center min-h-screen font-sans" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-landboard')

    <div id="main-content" class="main-content p-6 md:pt-4 w-full">
    <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
        <p><strong class="use-poppins">Edit Kamar</strong></p>
        <p class="text-[14px]">Berikut merupakan data dari kamar <strong>{{ $room->room_number }}</strong>. Anda dapat melakukan pengeditan.</p>
      </div>
        <div class="mt-6 bg-white shadow-lg rounded-lg p-8">
        @if ($errors->any())
          <ul class="mb-4 list-disc text-red-600 pl-5">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        @endif
        <div id="photo-warning" class="photo-warning">
          <i class="bi bi-exclamation-triangle-fill mr-2"></i>
          <strong>Peringatan:</strong> Kamar harus memiliki minimal 1 foto. Jika Anda menghapus semua foto lama, pastikan menambahkan foto baru.
        </div>

        <form action="{{ route('landboard.rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-600">Tipe Kamar</label>
              <input type="text" name="type" value="{{ old('type', $room->type) }}" required class="mt-1 w-full rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 focus:border-[#31c594] focus:ring-1 focus:ring-[#31c594]">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-600">Harga per Bulan</label>
              <input type="number" name="price" value="{{ old('price', $room->price) }}" required class="mt-1 w-full rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 focus:border-[#31c594] focus:ring-1 focus:ring-[#31c594]">
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-600">Jenis Kelamin yang Diizinkan</label>
              <select name="gender_type" required class="mt-1 w-full rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 focus:border-[#31c594] focus:ring-1 focus:ring-[#31c594]">
                <option value="male" {{ old('gender_type', $room->gender_type) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                <option value="female" {{ old('gender_type', $room->gender_type) === 'female' ? 'selected' : '' }}>Perempuan</option>
                <option value="mixed" {{ old('gender_type', $room->gender_type) === 'mixed' ? 'selected' : '' }}>Campuran</option>
              </select>
            </div>
          </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-600 mb-1">Foto Lama</label>
              <div id="existing-photos" class="photo-grid">
                @foreach ($room->photos as $photo)
                  <div class="photo-wrapper">
                    <img src="{{ asset('storage/' . $photo->path) }}" data-id="{{ $photo->id }}" class="transition" onclick="toggleSelectPhoto(this)">
                    <i class="bi bi-trash delete-icon"></i>
                    <input type="checkbox" name="delete_photos[]" value="{{ $photo->id }}" class="hidden">
                  </div>
                @endforeach
              </div>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-600 mb-1">Tambah Foto Baru</label>
              <div class="rounded-lg bg-gray-50" id="photo-container">
                <div class="group flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                  <input type="file" name="photos[]" 
                         class="text-gray-600 flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]" 
                         accept="image/*">
                  <button type="button" 
                          class="remove-button-photo bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-1" 
                          onclick="removePhotoField(this)">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
              <button type="button" 
                      id="add-photo-button"
                      class="mt-2 bg-[#31c594] hover:bg-[#2ba882] text-white px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2" 
                      onclick="addField('photo-container', 'photos[]', 'file')">
                <i class="bi bi-plus"></i>Tambah foto
              </button>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-600 mb-1">Fasilitas</label>
              <div class="rounded-lg bg-gray-50" id="facility-container">
                @forelse ($room->facilities as $facility)
                  <div class="group flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                    <input type="text" name="facilities[]" value="{{$facility->name}}" 
                           class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0" 
                           placeholder="Contoh: AC, WiFi, Lemari" 
                           required>
                    <button type="button" 
                            class="remove-button-facility bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-1" 
                            onclick="removeField(this)">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                @empty
                  <div class="group flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                    <input type="text" name="facilities[]" 
                           class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0" 
                           placeholder="Contoh: AC, WiFi, Lemari" 
                           required>
                    <button type="button" 
                            class="remove-button-facility bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-1" 
                            onclick="removeField(this)">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                @endforelse
              </div>
              <button type="button" 
                      id="add-facility-button"
                      class="mt-2 bg-[#31c594] hover:bg-[#2ba882] text-white px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2" 
                      onclick="addField('facility-container', 'facilities[]')">
                <i class="bi bi-plus"></i>Tambah fasilitas
              </button>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-600 mb-1">Aturan</label>
              <div class="rounded-lg bg-gray-50" id="rule-container">
                @forelse ($room->rules as $rule)
                  <div class="group flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                    <input type="text" name="rules[]" value="{{ $rule->name }}"
                           class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0" 
                           placeholder="Contoh: Tidak boleh merokok, Jam malam 22:00" 
                           required>
                    <button type="button" 
                            class="remove-button-rule bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-1" 
                            onclick="removeField(this)">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                @empty
                  <div class="group flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                    <input type="text" name="rules[]" 
                           class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0" 
                           placeholder="Contoh: Tidak boleh merokok, Jam malam 22:00" 
                           required>
                    <button type="button" 
                            class="remove-button-rule bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-1" 
                            onclick="removeField(this)">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                @endforelse
              </div>
              <button type="button" 
                      id="add-rule-button"
                      class="mt-2 bg-[#31c594] hover:bg-[#2ba882] text-white px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2" 
                      onclick="addField('rule-container', 'rules[]')">
                <i class="bi bi-plus"></i>Tambah aturan
              </button>
            </div>

            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-600 mb-1">Perbarui Untuk</label>
              <div class="space-y-2">
                <label class="inline-flex items-center">
                  <input type="radio" name="apply_all" value="0" checked class="text-green-600">
                  <span class="ml-2 text-sm text-gray-600">Hanya kamar ini</span>
                </label><br>
                <label class="inline-flex items-center">
                  <input type="radio" name="apply_all" value="1" class="text-green-600">
                  <span class="ml-2 text-sm text-gray-600">Semua kamar tipe ini ({{ $room->type }})</span>
                </label>
              </div>
            </div>

            <button id="save-button" type="submit" class="w-full bg-[#31c594] text-white px-8 py-4 rounded-lg text-base font-semibold transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-[#31c594]/30 disabled:opacity-50 disabled:cursor-not-allowed">
              <i class="bi bi-save mr-2"></i>Simpan
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

    <script>
    function updateActionButtonsVisibility() {
      const sections = [
        {
          selector: 'input[name="facilities[]"]',
          addBtnId: 'add-facility-button',
          removeBtnClass: 'remove-button-facility'
        },
        {
          selector: 'input[name="rules[]"]',
          addBtnId: 'add-rule-button',
          removeBtnClass: 'remove-button-rule'
        }
      ];

      sections.forEach(({ selector, addBtnId, removeBtnClass }) => {
        const inputs = document.querySelectorAll(selector);
        const filled = [...inputs].some(input => input.value.trim() !== '');
        const addBtn = document.getElementById(addBtnId);
        const removeBtns = document.querySelectorAll(`.${removeBtnClass}`);

        if (addBtn) {
          addBtn.classList.toggle('action-button-hidden', !filled);
        }

        removeBtns.forEach(btn => {
          btn.classList.toggle('action-button-hidden', !filled);
        });
      });
    }

    function updatePhotoWarning() {
      const totalOldPhotos = document.querySelectorAll('#existing-photos .photo-wrapper').length;
      const checkedOldPhotos = document.querySelectorAll('#existing-photos input[type="checkbox"]:checked').length;
      const remainingOldPhotos = totalOldPhotos - checkedOldPhotos;
      
      const newPhotoInputs = document.querySelectorAll('#photo-container input[type="file"]');
      const filledNewPhotos = [...newPhotoInputs].filter(input => input.files && input.files.length > 0).length;
      
      const warning = document.getElementById('photo-warning');
      const saveButton = document.getElementById('save-button');
      const willHaveNoPhotos = remainingOldPhotos === 0 && filledNewPhotos === 0;
      
      console.log('Total old photos:', totalOldPhotos);
      console.log('Checked old photos:', checkedOldPhotos);
      console.log('Remaining old photos:', remainingOldPhotos);
      console.log('Filled new photos:', filledNewPhotos);
      console.log('Will have no photos:', willHaveNoPhotos);
      
      if (willHaveNoPhotos) {
        warning.classList.add('show');
        saveButton.disabled = true;
        saveButton.classList.add('opacity-50', 'cursor-not-allowed', 'hover:translate-y-0', 'hover:shadow-none');
        saveButton.style.backgroundColor = '#94a3b8';
      } else {
        warning.classList.remove('show');
        saveButton.disabled = false;
        saveButton.classList.remove('opacity-50', 'cursor-not-allowed');
        saveButton.style.backgroundColor = '';
      }
    }

    function removeField(button) {
      const group = button.closest('.group');
      const container = group.parentElement;
      const allGroups = container.querySelectorAll('.group');

      if (allGroups.length > 1) {
        group.remove();
      } else {
        alert('Minimal 1 input harus ada.');
      }
      
      updateActionButtonsVisibility();
    }

    function removePhotoField(button) {
      const group = button.closest('.group');
      const container = group.parentElement;
      const allGroups = container.querySelectorAll('.group');

      if (allGroups.length > 1) {
        group.remove();
      } else {
        const input = group.querySelector('input[type="file"]');
        input.value = '';
      }
      
      updateActionButtonsVisibility();
      updatePhotoWarning();
    }

    function addField(containerId, inputName, type = 'text') {
      if (inputName === 'photos[]' && getPhotoInputCount() >= 7) {
        alert('Maksimal hanya bisa upload 7 foto.');
        return;
      }

      const container = document.getElementById(containerId);
      const div = document.createElement('div');
      div.className = 'group flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center';

      const placeholder = getPlaceholder(inputName);
      const inputClass = 'flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20';
      const fileInputClass = inputClass + ' file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]';

      let removeButtonClass = 'bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-1';
      let removeFunction = 'removeField(this)';
      
      if (inputName.includes('facilities')) {
        removeButtonClass = 'remove-button-facility ' + removeButtonClass;
      } else if (inputName.includes('rules')) {
        removeButtonClass = 'remove-button-rule ' + removeButtonClass;
      } else if (inputName.includes('photos')) {
        removeButtonClass = 'remove-button-photo ' + removeButtonClass;
        removeFunction = 'removePhotoField(this)';
      }

      div.innerHTML = `
        <input type="${type}" name="${inputName}" 
              class="text-gray-600 ${type === 'file' ? fileInputClass : inputClass}" 
              ${type === 'file' ? 'accept="image/*"' : `placeholder="${placeholder}"`} ${type !== 'file' ? 'required' : ''}>
        <button type="button" 
                class="${removeButtonClass}" 
                onclick="${removeFunction}">
          <i class="bi bi-trash"></i>
        </button>`;

      container.appendChild(div);
      
      const newInput = div.querySelector('input');
      newInput.addEventListener('input', function() {
        updateActionButtonsVisibility();
      });
      
      if (type === 'file') {
        newInput.addEventListener('change', function() {
          updatePhotoWarning();
        });
      }
      
      updateActionButtonsVisibility();
    }

    function getPlaceholder(inputName) {
      if (inputName.includes('facilities')) {
        return 'Contoh: Kasur, Meja Belajar, Kamar Mandi Dalam';
      } else if (inputName.includes('rules')) {
        return 'Contoh: Dilarang membawa tamu menginap';
      }
      return '';
    }

    function getPhotoInputCount() {
      return document.querySelectorAll('#photo-container input[type="file"]').length;
    }

    function getTotalPhotosAfterEdit() {
      const totalOldPhotos = document.querySelectorAll('#existing-photos .photo-wrapper').length;
      const checkedOldPhotos = document.querySelectorAll('#existing-photos input[type="checkbox"]:checked').length;
      const remainingOldPhotos = totalOldPhotos - checkedOldPhotos;
      
      const newPhotoInputs = document.querySelectorAll('#photo-container input[type="file"]');
      const filledNewPhotos = [...newPhotoInputs].filter(input => input.files && input.files.length > 0).length;
      
      return remainingOldPhotos + filledNewPhotos;
    }

    function validateForm(event) {
      const facilities = document.querySelectorAll('input[name="facilities[]"]');
      const hasFacility = [...facilities].some(input => input.value.trim() !== '');
      if (!hasFacility) {
        alert('Minimal 1 fasilitas harus diisi.');
        event.preventDefault();
        return false;
      }

      const rules = document.querySelectorAll('input[name="rules[]"]');
      const hasRule = [...rules].some(input => input.value.trim() !== '');
      if (!hasRule) {
        alert('Minimal 1 aturan harus diisi.');
        event.preventDefault();
        return false;
      }

      const totalPhotos = getTotalPhotosAfterEdit();
      if (totalPhotos === 0) {
        alert('Kamar harus memiliki minimal 1 foto. Pastikan ada foto lama yang tidak dihapus atau tambahkan foto baru.');
        event.preventDefault();
        return false;
      }

      if (totalPhotos > 7) {
        alert('Maksimal hanya boleh ada 7 foto total.');
        event.preventDefault();
        return false;
      }

      return true;
    }

    function toggleSelectPhoto(img) {
      const wrapper = img.closest('.photo-wrapper');
      const checkbox = wrapper.querySelector('input[type="checkbox"]');
      const selected = img.classList.toggle('photo-selected');
      checkbox.checked = selected;
      updatePhotoWarning();
    }

    document.addEventListener('DOMContentLoaded', function() {
      updateActionButtonsVisibility();
      updatePhotoWarning();
      
      // Event listeners untuk input yang sudah ada
      document.querySelectorAll('input[name="facilities[]"], input[name="rules[]"]').forEach(input => {
        input.addEventListener('input', updateActionButtonsVisibility);
      });
      
      // Event listeners untuk foto yang sudah ada
      document.querySelectorAll('input[name="photos[]"]').forEach(input => {
        input.addEventListener('change', updatePhotoWarning);
      });
      
      document.querySelectorAll('#existing-photos input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', updatePhotoWarning);
      });
    });
  </script>
</body>
</html>