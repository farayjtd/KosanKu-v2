<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>KosanKu - Login</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-cover bg-no-repeat bg-center flex items-center justify-center px-4 md:px-0" style="background-image: url('/assets/auth.png')">

  <div class="w-full max-w-md">
    <form 
      action="{{ route('login.process') }}" 
      method="POST" 
      class="bg-[#F7F9F4] px-6 py-8 md:px-10 md:py-10 rounded-3xl shadow-gray-100 shadow-xs w-full"
    >
      <h2 class="text-2xl font-semibold text-[#31c594] text-left mb-2 use-poppins leading-snug">
        Selamat Datang<br>di KosanKu!
      </h2>
      <p class="text-xs font-medium text-gray-500 text-left mb-6 use-poppins">
        Silahkan masuk menggunakan akun anda.
      </p>

      @csrf

      @if ($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-700 text-sm px-4 py-3 rounded-xl mb-4 break-words">
          {{ $errors->first() }}
        </div>
      @endif

      <label for="username" class="block text-sm font-medium text-gray-700 mt-2">
        <i class="bi bi-person-fill mr-2"></i>Username
      </label>
      <input 
        type="text" 
        id="username" 
        name="username" 
        value="{{ old('username') }}" 
        required 
        class="w-full mt-1 px-4 py-2 rounded-xl text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0"
      >

      <label for="password" class="block text-sm font-medium text-gray-700 mt-4">
        <i class="bi bi-lock-fill mr-2"></i>Password
      </label>
      <div class="relative">
        <input 
          type="password" 
          id="password" 
          name="password" 
          required 
          class="w-full mt-1 px-4 py-2 pr-10 rounded-xl text-sm bg-white border-1 border-gray-400 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0"
        >
        <button type="button" id="togglePassword" class="absolute right-3 top-5.5 -translate-y-1/2 text-gray-500 hover:text-[#31c594]">
          <i class="bi bi-eye-slash-fill" id="eyeIcon"></i>
        </button>
      </div>

      <a 
        href="{{ route('password.request') }}" 
        class="block mt-2 mb-4 text-sm text-right text-blue-500 underline hover:no-underline transition duration-200"
      >
        Lupa Password?
      </a>

      <button 
        type="submit" 
        class="w-2/3 mt-6 py-2 bg-[#31c594] text-white font-semibold rounded-xl mx-auto block hover:bg-[#2ab88d] duration-300 cursor-pointer"
      >
        Login
      </button>
    </form>
  </div>

  <script>
    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");
    const eyeIcon = document.getElementById("eyeIcon");

    togglePassword.addEventListener("click", function () {
      const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);
      eyeIcon.classList.toggle("bi-eye-fill");
      eyeIcon.classList.toggle("bi-eye-slash-fill");
    });
  </script>
</body>
</html>
