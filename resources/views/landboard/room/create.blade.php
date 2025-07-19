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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  @vite('resources/css/app.css')
  <style>
    .action-button-hidden {
      display: none !important;
    }
  </style>

</head>
<body class="bg-cover bg-no-repeat bg-center use-poppins-normal" style="background-image: url('/assets/auth.png')">
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
      <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
        <p><strong class="use-poppins">Buat Kamar </strong></p>
        <p class="text-[14px]">Disini, anda dapat membuat beberapa kamar dengan tipe yang sama sekaligus.</p>
      </div>
      <div class="max-w-full mt-6 bg-white rounded-2xl shadow-lg overflow-hidden">
        <!-- <div class="bg-gradient-to-r from-[#31c594] to-[#2ba882] text-white p-6 text-center">
          <h2 class="text-xl font-semibold">
            <i class="bi bi-plus-circle mr-2"></i>Tambah Kamar Baru
          </h2>
        </div> -->

        <div class="p-8">
          <form action="{{ route('landboard.rooms.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Tipe Kamar
                </label>
                <input type="text" name="type" 
                       class="text-gray-600 w-full px-3 py-3 border-2 border-gray-200 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20" 
                       value="{{ old('type') }}" 
                       placeholder="Contoh: Kamar Standard, Kamar Premium" 
                       required>
              </div>

              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Jumlah Kamar
                </label>
                <input type="number" name="room_quantity" 
                       class="text-gray-600 w-full px-3 py-3 border-2 border-gray-200 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20" 
                       min="1" 
                       value="{{ old('room_quantity', 1) }}" 
                       placeholder="Masukkan jumlah kamar" 
                       required>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Harga per Bulan
                </label>
                <input type="number" name="price" 
                       class="text-gray-600 w-full px-3 py-3 border-2 border-gray-200 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20" 
                       value="{{ old('price') }}" 
                       placeholder="Contoh: 500000" 
                       required>
              </div>

              <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Jenis Kelamin yang Diizinkan
                </label>
                <select name="gender_type" 
                        class="text-gray-600 w-full px-3 py-3 border-2 border-gray-200 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20" 
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
              <label class="block text-sm font-semibold text-gray-700 mb-2">Fasilitas Kamar
              </label>
              <div class="rounded-lg bg-gray-50" id="facility-container">
                <div class="group flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                  <input type="text" name="facilities[]" 
                         class="text-gray-600 flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20" 
                         placeholder="Contoh: AC, WiFi, Lemari" 
                         required>
                  <button type="button" 
                          class="remove-button-facility bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-1" 
                          onclick="removeField(this)">
                    <i class="bi bi-trash"></i>
                  </button>
                  <!-- <button type="button" 
                      id="add-facility-button"
                      class=" bg-[#31c594] hover:bg-[#2ba882] text-white px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2" 
                      onclick="addField('facility-container', 'facilities[]')">
                      <i class="bi bi-plus"></i>
                  </button> -->
                </div>
              </div>
              <button type="button" 
                      id="add-facility-button"
                      class="mt-2 bg-[#31c594] hover:bg-[#2ba882] text-white px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2" 
                      onclick="addField('facility-container', 'facilities[]')">Tambah Fasilitas
              </button>
            </div>

            {{-- Aturan --}}
            <div class="mb-6">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Aturan Kamar
              </label>
              <div class="rounded-lg bg-gray-50" id="rule-container">
                <div class="group flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                  <input type="text" name="rules[]" 
                         class="text-gray-600 flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20" 
                         placeholder="Contoh: Tidak boleh merokok, Jam malam 22:00" 
                         required>
                  <button type="button" 
                          class="remove-button-rule bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-1" 
                          onclick="removeField(this)">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
              <button type="button" 
                      id="add-rule-button"
                      class="mt-2 bg-[#31c594] hover:bg-[#2ba882] text-white px-4 py-2 rounded-md text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2" 
                      onclick="addField('rule-container', 'rules[]')">Tambah Aturan
              </button>
            </div>

            {{-- Foto Kamar --}}
            <div class="mb-8">
              <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Kamar
              </label>
              <div class="rounded-lg bg-gray-50" id="photo-container">
                <div class="group flex flex-col md:flex-row gap-3 mb-3 items-start md:items-center">
                  <input type="file" name="photos[]" 
                         class="text-gray-600 flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]" 
                         accept="image/*" 
                         required>
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
                      onclick="addField('photo-container', 'photos[]', 'file')">Tambah Foto
              </button>
            </div>

            <button type="submit" 
                    class="w-full bg-[#31c594] text-white px-8 py-4 rounded-lg text-base font-semibold transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-[#31c594]/30">
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
      {
        selector: 'input[name="photos[]"]',
        addBtnId: 'add-photo-button',
        removeBtnClass: 'remove-button-photo'
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

    function removeField(button) {
      const group = button.closest('.group');
      const container = group.parentElement;

      const allGroups = container.querySelectorAll('.group');

      if (allGroups.length > 1) {
        group.remove();
      } else {
        alert('Minimal 1 input harus ada.');
      }
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

      div.innerHTML = `
        <input type="${type}" name="${inputName}" 
              class="text-gray-600 ${type === 'file' ? fileInputClass : inputClass}" 
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

    function getPhotoInputCount() {
      return document.querySelectorAll('#photo-container input[type="file"]').length;
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

      if (getPhotoInputCount() > 7) {
        alert('Anda hanya boleh mengunggah maksimal 7 foto.');
        return false;
      }

      return true;
    }

  </script>
</body>
</html>