<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reset Password - KosanKu</title>
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f1ee;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .container {
      width: 100%;
      max-width: 460px;
      padding: 20px;
    }
    form {
      background: #fff;
      padding: 28px 32px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.06);
      border: 1px solid #ddd;
    }
    h2 {
      color: #4b3b2f;
      margin-bottom: 20px;
      font-size: 1.6em;
      text-align: center;
    }
    label {
      display: block;
      margin-top: 16px;
      margin-bottom: 6px;
      font-weight: 600;
      color: #4a4a4a;
    }
    input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      background-color: #fdfaf8;
    }
    button {
      margin-top: 24px;
      padding: 10px;
      width: 100%;
      background-color: #8b5e3c;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    button:hover {
      background-color: #6b4f3b;
    }
    .alert {
      background: #fcebea;
      color: #b91c1c;
      padding: 12px;
      margin-bottom: 20px;
      border-radius: 6px;
      font-size: 14px;
      border: 1px solid #f5c6cb;
      text-align: center;
    }
  </style>
</head>
<body>

  <div class="container">
    <form method="POST" action="{{ route('password.update') }}">
      <h2>Reset Password</h2>

      @csrf

      <input type="hidden" name="token" value="{{ $token }}">
      <input type="hidden" name="email" value="{{ $email }}">

      @if ($errors->any())
        <div class="alert">{{ $errors->first() }}</div>
      @endif

      <label for="password">Password Baru</label>
      <input type="password" id="password" name="password" required>

      <label for="password_confirmation">Konfirmasi Password</label>
      <input type="password" id="password_confirmation" name="password_confirmation" required>

      <button type="submit">Simpan Password Baru</button>
    </form>
  </div>

</body>
</html>
