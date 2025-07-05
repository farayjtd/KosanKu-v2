<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tambah Kamar</title>
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

    .card {
      background: #fffaf6;
      padding: 30px;
      border-radius: 14px;
      max-width: 800px;
      margin: auto;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
    }

    h2 {
      text-align: center;
      color: #5a4430;
      margin-bottom: 24px;
    }

    label {
      font-weight: 600;
      margin-top: 18px;
      display: block;
      color: #6b4e3d;
    }

    input, select {
      width: 100%;
      padding: 12px;
      margin-top: 6px;
      border: 1px solid #d6ccc2;
      border-radius: 8px;
      font-size: 14px;
      background: #fdfdfb;
      box-sizing: border-box;
    }

    .group {
      display: flex;
      gap: 10px;
      margin-top: 10px;
    }

    .group input[type="text"],
    .group input[type="file"] {
      flex: 1;
    }

    .remove-btn {
      background: #c84c43;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
    }

    .add-btn {
      margin-top: 10px;
      background: #a18064;
      color: white;
      border: none;
      padding: 10px 14px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 14px;
    }

    .add-btn:hover {
      background: #80644c;
    }

    button[type="submit"] {
      margin-top: 30px;
      width: 100%;
      padding: 14px;
      background: #6e5947;
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      cursor: pointer;
    }

    button[type="submit"]:hover {
      background: #5a4430;
    }

    .message, .error-list {
      max-width: 800px;
      margin: 0 auto 20px auto;
      font-weight: bold;
    }

    .message {
      color: green;
    }

    .error-list {
      color: red;
    }

    @media (max-width: 768px) {
      .main-content {
        padding: 20px;
      }

      .card {
        padding: 20px;
        margin: 20px;
      }

      .group {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

  {{-- Sidebar --}}
  @include('components.sidebar-landboard')

  <div class="main-content">

    {{-- Pesan Sukses --}}
    @if(session('success'))
      <p class="message">{{ session('success') }}</p>
    @endif

    {{-- Validasi Error --}}
    @if($errors->any())
      <ul class="error-list">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    @endif

    <div class="card">
      <h2>Tambah Kamar</h2>

      <form action="{{ route('landboard.rooms.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        @csrf

        <label>Tipe Kamar</label>
        <input type="text" name="type" value="{{ old('type') }}" required>

        <label>Jumlah Kamar</label>
        <input type="number" name="room_quantity" min="1" value="{{ old('room_quantity', 1) }}" required>

        <label>Harga per Bulan</label>
        <input type="number" name="price" value="{{ old('price') }}" required>

        <label>Jenis Kelamin yang Diizinkan</label>
        <select name="gender_type" required>
          <option value="mixed" {{ old('gender_type') == 'mixed' ? 'selected' : '' }}>Campuran</option>
          <option value="male" {{ old('gender_type') == 'male' ? 'selected' : '' }}>Laki-laki</option>
          <option value="female" {{ old('gender_type') == 'female' ? 'selected' : '' }}>Perempuan</option>
        </select>

        {{-- Fasilitas --}}
        <label>Fasilitas</label>
        <div id="facility-container">
          <div class="group">
            <input type="text" name="facilities[]" required>
            <button type="button" class="remove-btn" onclick="removeField(this)">X</button>
          </div>
        </div>
        <button type="button" class="add-btn" onclick="addField('facility-container', 'facilities[]')">+ Tambah Fasilitas</button>

        {{-- Aturan --}}
        <label>Aturan</label>
        <div id="rule-container">
          <div class="group">
            <input type="text" name="rules[]" required>
            <button type="button" class="remove-btn" onclick="removeField(this)">X</button>
          </div>
        </div>
        <button type="button" class="add-btn" onclick="addField('rule-container', 'rules[]')">+ Tambah Aturan</button>

        {{-- Foto Kamar --}}
        <label>Foto Kamar</label>
        <div id="photo-container">
          <div class="group">
            <input type="file" name="photos[]" accept="image/*" required>
            <button type="button" class="remove-btn" onclick="removeField(this)">X</button>
          </div>
        </div>
        <button type="button" class="add-btn" onclick="addField('photo-container', 'photos[]', 'file')">+ Tambah Foto</button>

        <button type="submit">Simpan</button>
      </form>
    </div>
  </div>

  <script>
    function removeField(button) {
      const group = button.closest('.group');
      const container = group.parentElement;
      if (container.querySelectorAll('.group').length > 1) {
        group.remove();
      } else {
        alert('Minimal 1 input harus ada.');
      }
    }

    function addField(containerId, inputName, type = 'text') {
      const container = document.getElementById(containerId);
      const div = document.createElement('div');
      div.className = 'group';
      div.innerHTML = `
        <input type="${type}" name="${inputName}" ${type === 'file' ? 'accept="image/*"' : ''} required>
        <button type="button" class="remove-btn" onclick="removeField(this)">X</button>`;
      container.appendChild(div);
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

      return true;
    }
  </script>
</body>
</html>
