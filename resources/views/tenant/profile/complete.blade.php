<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lengkapi Profil Tenant</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  @vite('resources/css/app.css')
</head>
<body class="use-poppins-normal bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
    <div id="main-content" class="main-content p-6 md:pt-4">
        <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
          <p><strong class="use-poppins">Lengkapi Data Anda</strong></p>
          <p class="text-[14px]">Anda diwajibkan untuk mengisi semua data agar mempermudah dalam komunikasi dengan pemilik kos.</p>
        </div>  
        <div class="bg-white rounded-xl shadow-lg p-6 max-w-full mt-6">
          {{-- Error Global --}}
          @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
              <div class="flex items-center mb-2">
                <i class="bi bi-exclamation-triangle-fill mr-2"></i>
                <strong>Terdapat kesalahan:</strong>
              </div>
              <ul class="list-disc list-inside ml-6">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('tenant.profile.complete.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Personal Information Section --}}
            <div class="mb-8">
              <div class="flex items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Informasi Pribadi</h3>
              </div>
              <div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                          class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                    @error('name') 
                      <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                          class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                    @error('email') 
                      <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Nomor HP Utama</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                          class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                    @error('phone') 
                      <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Jenis Kelamin</label>
                    <select name="gender" required
                            class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                      <option value="">-- Pilih Jenis Kelamin --</option>
                      <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                      <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender') 
                      <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                  </div>

                  <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Alamat Asal</label>
                    <input type="text" name="address" value="{{ old('address') }}" required
                          class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                    @error('address') 
                      <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                  </div>
                </div>
              </div>
            </div>

            {{-- Activity Information Section --}}
            <div class="mb-8">
              <div class="flex items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Informasi Aktivitas</h3>
              </div>
              <div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Pekerjaan</label>
                    <input type="text" name="activity_type" value="{{ old('activity_type') }}" 
                          placeholder="Contoh: Mahasiswa, Pegawai"
                          class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                    @error('activity_type') 
                      <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Nama Institusi</label>
                    <input type="text" name="institution_name" value="{{ old('institution_name') }}" 
                          class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                    @error('institution_name') 
                      <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                  </div>
                </div>
              </div>
            </div>

            {{-- Photo Documents Section --}}
            <div class="mb-8">
              <div class="flex items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Dokumen Foto</h3>
              </div>
              <div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Foto Avatar</label>
                    <input type="file" name="avatar" accept="image/*" 
                          class="text-gray-600 flex-1 px-3 w-full py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]">
                    @error('avatar') 
                      <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Foto KTP / Identitas</label>
                    <input type="file" name="identity_photo" accept="image/*" 
                          class="text-gray-600 flex-1 px-3 w-full py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]">
                    @error('identity_photo') 
                      <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                  </div>

                  <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Foto Diri</label>
                    <input type="file" name="selfie_photo" accept="image/*" 
                          class="text-gray-600 flex-1 px-3 w-full py-2 border border-gray-300 rounded-md text-sm transition-all duration-200 focus:outline-none focus:border-[#31c594] focus:ring-2 focus:ring-[#31c594]/20 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#31c594] file:text-white hover:file:bg-[#2ba882]">
                    @error('selfie_photo') 
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
                    <input type="text" name="bank_name" value="{{ old('bank_name') }}" required
                          class="text-gray-600 w-full mt-1 px-4 py-2 pr-10 rounded-md text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
                    @error('bank_name') 
                      <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Nomor Rekening</label>
                    <input type="text" name="bank_account" value="{{ old('bank_account') }}" required
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
                Simpan Profil
              </button>
            </div>
          </form>
        </div>
    </div>
</body>
</html>