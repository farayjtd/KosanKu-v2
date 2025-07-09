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
    .action-button-hidden {
      display: none !important;
    }
  </style>
</head>
<body class="bg-cover bg-no-repeat bg-center min-h-screen font-sans" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-landboard')

    <div id="main-content" class="main-content p-6 md:pt-4 w-full">
      <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg">
        <div class="bg-gradient-to-r from-[#31c594] to-[#2ba882] rounded-t-2xl text-white p-6 text-center">
          <h2 class="text-xl font-bold font-poppins mb-2"><i class="bi bi-pencil-square mr-2"></i>Edit Kamar</h2>
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
              <div class="rounded-lg bg-gray-50" id="photo-container">
                <div class="group flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                  <input type="file" name="photos[]" 
                         class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]" 
                         accept="image/*">
                  <button type="button" 
                          class="remove-button-photo bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-1" 
                          onclick="removeField(this)">
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
              <label class="block font-medium">Fasilitas</label>
              <div class="rounded-lg bg-gray-50" id="facility-container">
                @forelse ($room->facilities as $facility)
                  <div class="group flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                    <input type="text" name="facilities[]" value="{{$facility->name}}" 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20" 
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
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20" 
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
              <label class="block font-medium">Aturan</label>
              <div class="rounded-lg bg-gray-50" id="rule-container">
                @forelse ($room->rules as $rule)
                  <div class="group flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                    <input type="text" name="rules[]" value="{{ $rule->name }}"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20" 
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
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20" 
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

            <button type="submit" class="w-full bg-[#31c594] text-white px-8 py-4 rounded-lg text-base font-semibold transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-[#31c594]/30">
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
        },
        // {
        //   selector: 'input[name="photos[]"]',
        //   addBtnId: 'add-photo-button',
        //   removeBtnClass: 'remove-button-photo'
        // }
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

    
    document.addEventListener('DOMContentLoaded', function() {
      const sidebar = document.getElementById('sidebar');
      const mainContent = document.getElementById('main-content');
      const toggleBtn = document.getElementById('toggleSidebar');
      updateActionButtonsVisibility();
      document.querySelectorAll('input[name="facilities[]"], input[name="rules[]"], input[name="photos[]"]').forEach(input => {
        input.addEventListener('input', updateActionButtonsVisibility);
      });
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

    function removeField(button) {
      const group = button.closest('.group');
      const container = group.parentElement;
      const allGroups = container.querySelectorAll('.group');

      if (allGroups.length > 1) {
        group.remove();
      } else {
        alert('Minimal 1 input harus ada.');
      }
      
      // Update visibility after removal
      updateActionButtonsVisibility();
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

      // Determine remove button class based on input type
      let removeButtonClass = 'bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-1';
      if (inputName.includes('facilities')) {
        removeButtonClass = 'remove-button-facility ' + removeButtonClass;
      } else if (inputName.includes('rules')) {
        removeButtonClass = 'remove-button-rule ' + removeButtonClass;
      } else if (inputName.includes('photos')) {
        removeButtonClass = 'remove-button-photo ' + removeButtonClass;
      }

      div.innerHTML = `
        <input type="${type}" name="${inputName}" 
              class="${type === 'file' ? fileInputClass : inputClass}" 
              ${type === 'file' ? 'accept="image/*"' : `placeholder="${placeholder}"`} required>
        <button type="button" 
                class="${removeButtonClass}" 
                onclick="removeField(this)">
          <i class="bi bi-trash"></i>
        </button>`;

      container.appendChild(div);
      
      // Add event listener to new input
      const newInput = div.querySelector('input');
      newInput.addEventListener('input', updateActionButtonsVisibility);
      
      // Update visibility after addition
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

    function validateForm() {
      const checks = [
        { selector: 'input[name="facilities[]"]', msg: 'Minimal 1 fasilitas harus diisi.' },
        { selector: 'input[name="rules[]"]', msg: 'Minimal 1 aturan harus diisi.' }
      ];

      // Check facilities and rules
      for (const { selector, msg } of checks) {
        if (![...document.querySelectorAll(selector)].some(input => input.value.trim() !== '')) {
          alert(msg);
          return false;
        }
      }

      // Check photos (new photos or existing photos not deleted)
      const photoInputs = document.querySelectorAll('input[name="photos[]"]');
      const existingChecked = document.querySelectorAll('input[name="delete_photos[]"]:checked');
      const totalOldPhotos = document.querySelectorAll('#existing-photos input[type="checkbox"]').length;
      const hasOldPhotoRemaining = totalOldPhotos > existingChecked.length;
      const hasNewPhoto = [...photoInputs].some(input => input.value !== '');

      if (!hasNewPhoto && !hasOldPhotoRemaining) {
        alert('Minimal harus ada 1 foto (lama atau baru).');
        return false;
      }

      if (getPhotoInputCount() > 7) {
        alert('Anda hanya boleh mengunggah maksimal 7 foto.');
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