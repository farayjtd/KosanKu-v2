<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lupa Password - KosanKu</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-cover bg-no-repeat bg-center flex items-center justify-center px-4 md:px-0" style="background-image: url('/assets/auth.png')">

  <div class="bg-[#F7F9F4] px-8 py-10 rounded-3xl shadow-gray-100 shadow-xs w-full max-w-md">
    <h2 class="use-poppins text-2xl font-semibold text-[#31c594] text-left mb-2">Reset Password</h2>
    <h2 class="use-poppins text-xs font-semibold text-gray-500 text-left mb-6">Masukkan email valid, dan tunggu hingga <br> kami mengirimkan email ke anda</h2>

    @if (session('status'))
      <div class="bg-green-100 text-green-700 text-sm px-4 py-3 rounded-md text-center mb-4">
        {{ session('status') }}
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf

      <label for="email" class="block text-sm font-semibold text-gray-700 mt-2">Email Anda</label>
      <input 
        type="email" 
        id="email" 
        name="email" 
        required 
        class="w-full mt-1 px-4 py-2 pr-10 rounded-xl text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0"
      >

      <button 
        type="submit" 
        class="w-2/3 mt-6 py-2 bg-[#31c594] text-white font-semibold rounded-xl mx-auto block hover:bg-[#2ab88d] duration-300 cursor-pointer"
      >
        Kirim Link Reset
      </button>
    </form>
  </div>

</body>
</html>
