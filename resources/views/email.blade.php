<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lupa Password - KosanKu</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f8f4f1;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .card {
      background: #fff;
      padding: 30px 36px;
      border-radius: 10px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
      max-width: 400px;
      width: 100%;
    }

    h2 {
      margin-top: 0;
      color: #5c3c2d;
      text-align: center;
    }

    label {
      display: block;
      margin: 16px 0 6px;
      color: #333;
      font-weight: 600;
    }

    input[type="email"] {
      width: 100%;
      padding: 10px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 8px;
      background-color: #fdfaf8;
    }

    button {
      margin-top: 20px;
      padding: 10px;
      width: 100%;
      background-color: #8b5e3c;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 15px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background-color: #6b4f3b;
    }

    .status {
      background-color: #d1fae5;
      color: #065f46;
      padding: 10px;
      border-radius: 6px;
      font-size: 14px;
      text-align: center;
      margin-top: 16px;
    }
  </style>
</head>
<body>

  <div class="card">
    <h2>Reset Password</h2>

    @if (session('status'))
      <div class="status">
        {{ session('status') }}
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf
      <label for="email">Email Anda</label>
      <input type="email" id="email" name="email" required>

      <button type="submit">Kirim Link Reset</button>
    </form>
  </div>

</body>
</html>
