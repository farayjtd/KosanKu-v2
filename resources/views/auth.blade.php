<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>KosanKu - Login</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f1ee;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
    }

    .container {
      width: 100%;
      max-width: 460px;
      padding: 20px;
    }

    .header {
      text-align: center;
      margin-top: 60px;
    }

    .header h1 {
      color: #6b4f3b;
      font-size: 2.4em;
      margin-bottom: 6px;
    }

    .header p {
      color: #888;
      font-size: 1em;
    }

    form {
      background: #fff;
      padding: 24px 28px;
      margin: 20px 0;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.06);
      border: 1px solid #ddd;
    }

    h2 {
      color: #4b3b2f;
      margin-bottom: 14px;
      font-size: 1.4em;
      text-align: center;
    }

    label {
      display: block;
      margin-top: 14px;
      font-weight: 600;
      color: #4a4a4a;
    }

    input {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      background-color: #fdfaf8;
    }

    button {
      margin-top: 20px;
      padding: 10px;
      width: 100%;
      background: #8b5e3c;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background: #6b4f3b;
    }

    .alert {
      background: #fcebea;
      color: #b91c1c;
      padding: 12px;
      margin-bottom: 15px;
      border-radius: 6px;
      font-size: 14px;
      border: 1px solid #f5c6cb;
    }

    .forgot-link {
      display: block;
      margin-top: 12px;
      text-align: center;
      font-size: 14px;
      color: #8b5e3c;
      text-decoration: none;
    }

    .forgot-link:hover {
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .container {
        padding: 16px;
      }

      form {
        padding: 20px;
      }

      h2 {
        font-size: 1.2em;
      }

      .header h1 {
        font-size: 2em;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="header">
      <h1>KosanKu</h1>
      <p>Login ke akun Anda</p>
    </div>

    <form action="{{ route('login.process') }}" method="POST">
      <h2>Masuk Akun</h2>
      @csrf

      @if ($errors->any())
        <div class="alert">
          {{ $errors->first() }}
        </div>
      @endif

      <label for="username">Username</label>
      <input type="text" id="username" name="username" value="{{ old('username') }}" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Login</button>

      <a href="{{ route('password.request') }}" class="forgot-link">Lupa Password?</a>
    </form>
  </div>

</body>
</html>
