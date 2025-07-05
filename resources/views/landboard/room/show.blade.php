<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Detail Kamar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      display: flex;
      background: #f0f2f5;
    }

    .main-content {
      flex: 1;
      padding: 30px;
      background: #f9f9f9;
    }

    h2 {
      margin-bottom: 20px;
      color: #2d3748;
    }

    h3 {
      margin-top: 24px;
      margin-bottom: 10px;
      color: #374151;
    }

    .section {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .section p,
    .section li {
      font-size: 14px;
      color: #374151;
    }

    .section strong {
      color: #111827;
    }

    img {
      width: 200px;
      border-radius: 8px;
      margin: 10px 10px 0 0;
      border: 1px solid #ccc;
      object-fit: cover;
    }

    ul {
      padding-left: 20px;
      margin: 0;
    }

    @media (max-width: 768px) {
      .main-content {
        padding: 20px;
      }

      img {
        width: 100%;
        max-width: 100%;
        height: auto;
      }
    }
  </style>
</head>
<body>

  @include('components.sidebar-landboard')

  <div class="main-content">
    <h2>Detail Kamar: {{ $room->room_number }}</h2>

    <div class="section">
      <p><strong>Tipe:</strong> {{ $room->type }}</p>
      <p><strong>Harga:</strong> Rp{{ number_format($room->price, 0, ',', '.') }}</p>
      <p><strong>Gender:</strong> {{ ucfirst($room->gender_type) }}</p>
      <p><strong>Status:</strong> {{ ucfirst($room->status) }}</p>
    </div>

    <div class="section">
      <h3>Foto Kamar</h3>
      @forelse ($room->photos as $photo)
        <img src="{{ asset('storage/' . $photo->path) }}" alt="Foto Kamar">
      @empty
        <p>Tidak ada foto tersedia.</p>
      @endforelse
    </div>

    <div class="section">
      <h3>Fasilitas</h3>
      @if ($room->facilities->isEmpty())
        <p>Tidak ada fasilitas ditambahkan.</p>
      @else
        <ul>
          @foreach ($room->facilities as $facility)
            <li>{{ $facility->name }}</li>
          @endforeach
        </ul>
      @endif
    </div>

    <div class="section">
      <h3>Aturan</h3>
      @if ($room->rules->isEmpty())
        <p>Tidak ada aturan ditentukan.</p>
      @else
        <ul>
          @foreach ($room->rules as $rule)
            <li>{{ $rule->name }}</li>
          @endforeach
        </ul>
      @endif
    </div>
  </div>

</body>
</html>
