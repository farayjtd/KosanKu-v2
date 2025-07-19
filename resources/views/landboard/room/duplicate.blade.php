<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Duplikat Kamar</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  @vite('resources/css/app.css')
</head>

<body class="bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-landboard')

    <div id="main-content" class="main-content p-6 md:pt-4 flex-1">
      <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
        <p><strong class="use-poppins">Duplikat Kamar</strong></p>
        <p class="text-[14px]">Anda dapat melakukan duplikasi dari kamar <strong>{{ $room->room_number }}</strong>.</p>
      </div>

      <div class="max-w-full p-6 bg-white rounded-xl mt-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-6">
          <div class="flex items-center mb-4">
            <h4 class="text-lg font-semibold text-gray-800 ml-3">Informasi Duplikasi</h4>
          </div>
          
          <div class="rounded-lg p-4 mb-6 border-gray-200 border-1 bg-gray-50">
            <p class="text-sm text-gray-700 mb-2">
              Duplikasi akan menyalin semua data kamar seperti:
            </p>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-3">
              <div class="flex items-center text-sm text-gray-700">
                <i class="bi bi-check-circle-fill text-blue-500 mr-2"></i>
                Fasilitas
              </div>
              <div class="flex items-center text-sm text-gray-700">
                <i class="bi bi-check-circle-fill text-blue-500 mr-2"></i>
                Aturan
              </div>
              <div class="flex items-center text-sm text-gray-700">
                <i class="bi bi-check-circle-fill text-blue-500 mr-2"></i>
                Harga
              </div>
              <div class="flex items-center text-sm text-gray-700">
                <i class="bi bi-check-circle-fill text-blue-500 mr-2"></i>
                Gender
              </div>
              <div class="flex items-center text-sm text-gray-700">
                <i class="bi bi-check-circle-fill text-blue-500 mr-2"></i>
                Foto
              </div>
            </div>
          </div>

          <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="border-b border-gray-200 pb-2 md:border-b-0 md:border-r md:border-gray-200 md:pr-4">
                <span class="text-gray-600 font-medium text-sm">Nomor Terakhir:</span>
                <span class="text-gray-800 font-semibold text-sm ml-2">{{ $lastNumber }}</span>
              </div>
              <div class="pt-2 md:pt-0 md:pl-4">
                <span class="text-gray-600 font-medium text-sm">Kamar Baru Dimulai Dari:</span>
                <span class="text-[#31c594] font-semibold text-sm ml-2">{{ $room->type }}-{{ $lastNumber + 1 }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-6">
          <div class="flex items-center mb-4">
            <h4 class="text-lg font-semibold text-gray-800">Buat Duplikat</h4>
          </div>

          <form method="POST" action="{{ route('landboard.rooms.duplicate', $room->id) }}" class="space-y-6">
            @csrf

            <div>
              <label for="room_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="bi bi-hash mr-1"></i>
                Jumlah Kamar yang Akan Dibuat:
              </label>
              <div class="relative">
                <input 
                  type="number" 
                  name="room_quantity" 
                  id="room_quantity" 
                  min="1" 
                  max="50"
                  required
                  class="text-gray-600 w-full mt-1 px-4 py-3 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0"
                  placeholder="Masukkan jumlah kamar (1-50)"
                />
              </div>
            </div>

              <button 
                type="submit"
                class="w-full bg-[#31c594] text-white px-8 py-3 rounded-lg text-sm font-semibold transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-[#31c594]/30"
              >
                <i class="bi bi-files mr-2"></i>
                Duplikat Sekarang
              </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
      const form = document.querySelector('form');
      const quantityInput = document.getElementById('room_quantity');

      form.addEventListener('submit', function(e) {
        const quantity = parseInt(quantityInput.value);
        
        if (quantity < 1 || quantity > 50) {
          e.preventDefault();
          alert('Jumlah kamar harus antara 1-50');
          quantityInput.focus();
          return;
        }

        const confirmed = confirm(`Apakah Anda yakin ingin membuat ${quantity} kamar baru?`);
        if (!confirmed) {
          e.preventDefault();
        }
      });
  </script>
</body>
</html>