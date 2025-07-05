<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Daftar Kamar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      background: #f5f3f0;
    }

    .main-content {
      flex: 1;
      padding: 30px;
    }

    h2 {
      margin-bottom: 20px;
      color: #5a4430;
    }

    .card-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .card {
      width: 260px;
      background: #fffaf6;
      border: 1px solid #e0dcd5;
      border-radius: 12px;
      padding: 16px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }

    .photo-carousel {
      position: relative;
      width: 100%;
      height: 150px;
      overflow: hidden;
      border-radius: 8px;
      margin-bottom: 10px;
    }

    .carousel-img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      display: none;
    }

    .carousel-img.active {
      display: block;
    }

    .carousel-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(0,0,0,0.4);
      color: white;
      border: none;
      padding: 4px 8px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
    }

    .carousel-btn.prev {
      left: 6px;
    }

    .carousel-btn.next {
      right: 6px;
    }

    .status {
      font-size: 12px;
      padding: 4px 8px;
      border-radius: 4px;
      color: white;
      display: inline-block;
    }

    .status.available {
      background: #10b981;
    }

    .status.booked {
      background: #f59e0b;
    }

    .status.occupied {
      background: #ef4444;
    }

    .button, button {
      margin-top: 6px;
      display: inline-block;
      background: #3b82f6;
      color: white;
      border: none;
      padding: 6px 10px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      text-decoration: none;
    }

    .button:hover, button:hover {
      background: #2563eb;
    }

    .delete-btn {
      background: #ef4444;
    }

    .delete-btn:hover {
      background: #b91c1c;
    }

    form {
      margin-top: 10px;
    }

    input[type="text"], select {
      width: 100%;
      padding: 6px 8px;
      margin-top: 6px;
      border-radius: 6px;
      border: 1px solid #d6ccc2;
      font-size: 14px;
      box-sizing: border-box;
    }

    p {
      margin: 4px 0;
      font-size: 14px;
    }
  </style>
</head>
<body>

  @include('components.sidebar-landboard')

  <div class="main-content">
    <h2>Daftar Kamar</h2>

    @if (session('success'))
      <p style="color: green;">{{ session('success') }}</p>
    @elseif (session('error'))
      <p style="color: red;">{{ session('error') }}</p>
    @endif

    {{-- Filter dan Sort --}}
    <form method="GET" style="margin-bottom: 20px; display: flex; gap: 10px; align-items: center;">
      <label>Status:
        <select name="status" onchange="this.form.submit()">
          <option value="">Semua</option>
          <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
          <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Booked</option>
          <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
        </select>
      </label>

      <label>Urutkan:
        <select name="sort" onchange="this.form.submit()">
          <option value="">-- Pilih --</option>
          <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
          <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Termahal</option>
          <option value="room_az" {{ request('sort') == 'room_az' ? 'selected' : '' }}>No Kamar A-Z</option>
          <option value="room_za" {{ request('sort') == 'room_za' ? 'selected' : '' }}>No Kamar Z-A</option>
        </select>
      </label>
    </form>

    <div class="card-container">
      @foreach ($rooms as $room)
        <div class="card">
          <div class="photo-carousel" data-room-id="{{ $room->id }}">
            @forelse ($room->photos as $index => $photo)
              <img src="{{ asset('storage/' . $photo->path) }}" 
                   class="carousel-img {{ $index === 0 ? 'active' : '' }}" 
                   data-index="{{ $index }}">
            @empty
              <img src="https://via.placeholder.com/240x140?text=No+Image" class="carousel-img active" />
            @endforelse

            @if ($room->photos->count() > 1)
              <button class="carousel-btn prev" onclick="prevPhoto({{ $room->id }})">&#10094;</button>
              <button class="carousel-btn next" onclick="nextPhoto({{ $room->id }})">&#10095;</button>
            @endif
          </div>

          <h4>{{ $room->room_number }}</h4>
          <p><strong>{{ $room->type }}</strong></p>
          <p>Rp{{ number_format($room->price, 0, ',', '.') }}</p>
          <p>Gender: {{ ucfirst($room->gender_type) }}</p>
          <p>Status: <span class="status {{ $room->status }}">{{ ucfirst($room->status) }}</span></p>

          <a href="{{ route('landboard.rooms.duplicate-form', $room->id) }}" class="button">Duplikat</a>
          <a href="{{ route('landboard.rooms.show', $room->id) }}" class="button">Detail</a>
          <a href="{{ route('landboard.rooms.edit-form', $room->id) }}" class="button">Edit</a>

          <form action="{{ route('landboard.rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kamar ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete-btn">Hapus</button>
          </form>
        </div>
      @endforeach
    </div>
  </div>

  <script>
    function nextPhoto(roomId) {
      const container = document.querySelector(`.photo-carousel[data-room-id="${roomId}"]`);
      const images = container.querySelectorAll('.carousel-img');
      let activeIndex = [...images].findIndex(img => img.classList.contains('active'));
      images[activeIndex].classList.remove('active');
      const nextIndex = (activeIndex + 1) % images.length;
      images[nextIndex].classList.add('active');
    }

    function prevPhoto(roomId) {
      const container = document.querySelector(`.photo-carousel[data-room-id="${roomId}"]`);
      const images = container.querySelectorAll('.carousel-img');
      let activeIndex = [...images].findIndex(img => img.classList.contains('active'));
      images[activeIndex].classList.remove('active');
      const prevIndex = (activeIndex - 1 + images.length) % images.length;
      images[prevIndex].classList.add('active');
    }
  </script>

</body>
</html>
