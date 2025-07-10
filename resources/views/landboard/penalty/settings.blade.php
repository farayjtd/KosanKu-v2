<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pengaturan Penalti</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;500&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="/style/font.css">
  @vite('resources/css/app.css')
  
</head>
<body class="use-poppins-normal bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    {{-- Sidebar --}}
    @include('components.sidebar-landboard')

    <main id="main-content" class="main-content flex-1 p-4 md:p-6 md:ml-[240px] transition-all duration-300 ease-in-out">
      <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
        <p><strong class="use-poppins">Pengaturan Penalti</strong></p>
        <p class="text-[14px]">Atur penalti</p>
      </div>
      <div class="mt-6 max-w-full overflow-hidden rounded-2xl shadow-lg bg-white">
        {{-- Header --}}
        <!-- <div class="rounded-t-2xl bg-gradient-to-r from-[#31c594] to-[#2ba882] p-6 text-center text-white">
          <h2 class="use-poppins text-2xl font-bold"><i class="bi bi-sliders mr-2"></i>Pengaturan Penalti</h2>
        </div> -->

        <div class="p-6 bg-white rounded-xl">
          {{-- Flash --}}
          @if(session('success'))
            <div class="mb-4 flex items-start gap-2 rounded-lg border border-emerald-300 bg-emerald-100 px-4 py-2 text-sm text-emerald-700">
              <i class="bi bi-check-circle pt-0.5"></i><span>{{ session('success') }}</span>
            </div>
          @endif

          @if($errors->any())
            <div class="mb-4 flex items-start gap-2 rounded-lg border border-red-300 bg-red-100 px-4 py-2 text-sm text-red-700">
              <i class="bi bi-exclamation-triangle pt-0.5"></i>
              <ul class="list-disc pl-4">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('landboard.penalty.update') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Aktifkan penalti --}}
            <label class="flex items-center gap-3 font-semibold text-gray-700">
              <input type="checkbox" id="penaltyEnabled" name="is_penalty_enabled" {{ $landboard->is_penalty_enabled ? 'checked' : '' }} class="h-5 w-5 rounded">
              Aktifkan Penalti
            </label>

            {{-- Denda telat --}}
            <div>
              <label class="mb-1 block text-sm text-gray-700">Jumlah Denda Telat Bayar (Rp)</label>
              <input type="number" name="late_fee_amount" value="{{ old('late_fee_amount', $landboard->late_fee_amount) }}" class="penalty-field w-full rounded-lg text-gray-600 px-3 py-2 border-gray-400 border-1 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
            </div>

            <div>
              <label class="mb-1 block text-sm text-gray-700">Jumlah Hari Setelah Jatuh Tempo Sebelum Denda (hari)</label>
              <input type="number" name="late_fee_days" value="{{ old('late_fee_days', $landboard->late_fee_days) }}" class="penalty-field w-full rounded-lg text-gray-600 px-3 py-2 border-gray-400 border-1 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
            </div>

            {{-- Penalti keluar --}}
            <label class="flex items-center gap-3 text-gray-700 font-semibold">
              <input type="checkbox" id="moveoutEnabled" name="is_penalty_on_moveout" {{ $landboard->is_penalty_on_moveout ? 'checked' : '' }} class="h-5 w-5 rounded">
              Penalti Jika Keluar Sebelum Masa Sewa Habis
            </label>

            <div>
              <label class="mb-1 block text-sm  text-gray-700">Nominal Penalti Keluar Sebelum Waktu (Rp)</label>
              <input type="number" name="moveout_penalty_amount" value="{{ old('moveout_penalty_amount', $landboard->moveout_penalty_amount) }}" class="moveout-field w-full rounded-lg text-gray-600 px-3 py-2 border-gray-400 border-1 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
            </div>

            {{-- Penalti pindah kamar --}}
            <label class="flex items-center gap-3 text-gray-700 font-semibold">
              <input type="checkbox" id="roomChangeEnabled" name="is_penalty_on_room_change" {{ $landboard->is_penalty_on_room_change ? 'checked' : '' }} class="h-5 w-5 rounded">
              Penalti Jika Pindah Kamar
            </label>

            <div>
              <label class="mb-1 block text-sm text-gray-700">Nominal Penalti Pindah Kamar (Rp)</label>
              <input type="number" name="room_change_penalty_amount" value="{{ old('room_change_penalty_amount', $landboard->room_change_penalty_amount) }}" class="roomchange-field w-full rounded-lg text-gray-600 px-3 py-2 border-gray-400 border-1 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0">
            </div>

            {{-- Perpanjangan Sewa --}}
            <div>
            <label for="decision_days_before_end" class="mb-1 block text-sm text-gray-700">
                Tampilkan Tombol Perpanjangan Sewa <span id="days-preview">{{ old('decision_days_before_end', $landboard->decision_days_before_end) }}</span> Hari Sebelum Habis
              </label>
              <input type="number" name="decision_days_before_end" id="decision_days_before_end" value="{{ old('decision_days_before_end', $landboard->decision_days_before_end) }}" class="late-fee-amount w-full rounded-lg text-gray-600 px-3 py-2 border-gray-400 border-1 focus:outline-none focus:ring-1 focus:ring-[#31c594] focus:border-0" oninput="document.getElementById('days-preview').innerText = this.value">
            </div>

            <button type="submit" class="w-full rounded-lg bg-[#31c594] px-8 py-3 text-base font-semibold text-white transition duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-[#31c594]/30">
              <i class="bi bi-save mr-2"></i>Simpan Pengaturan
            </button>
          </form>
        </div>
      </div>
    </main>
  </div>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const mainContent = document.getElementById('main-content');
  const toggleBtn = document.getElementById('toggleSidebar');
  
  const overlay = document.createElement('div');
  overlay.className = 'mobile-overlay';
  overlay.id = 'mobile-overlay';
  document.body.appendChild(overlay);

  function initializeSidebar() {
    if (window.innerWidth <= 768) {
      if (sidebar) {
        sidebar.classList.add('collapsed');
        sidebar.classList.remove('mobile-expanded');
      }
      if (mainContent) {
        mainContent.classList.add('collapsed');
      }
      overlay.classList.remove('active');
    } else {
      if (sidebar) {
        sidebar.classList.remove('mobile-expanded');
      }
      overlay.classList.remove('active');
    }
  }

  initializeSidebar();

  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener('click', function() {
      if (window.innerWidth <= 768) {
        if (sidebar.classList.contains('mobile-expanded')) {
          sidebar.classList.remove('mobile-expanded');
          sidebar.classList.add('collapsed');
          overlay.classList.remove('active');
        } else {
          sidebar.classList.remove('collapsed');
          sidebar.classList.add('mobile-expanded');
          overlay.classList.add('active');
        }
      } else {
        sidebar.classList.toggle('collapsed');
        if (mainContent) {
          mainContent.classList.toggle('collapsed');
        }
      }
    });
  }
  
  overlay.addEventListener('click', function() {
    if (window.innerWidth <= 768) {
      sidebar.classList.remove('mobile-expanded');
      sidebar.classList.add('collapsed');
      overlay.classList.remove('active');
    }
  });

  window.addEventListener('resize', function() {
    initializeSidebar();
  });

      // === enable / disable inputs ===
      const bindToggle = (checkboxId, fieldSelector) => {
        const cb = document.getElementById(checkboxId);
        const fields = document.querySelectorAll(fieldSelector);
        const setState = () => {
          fields.forEach(f => {
            f.disabled = !cb.checked;
            f.classList.toggle('opacity-50', !cb.checked);
            f.classList.toggle('cursor-not-allowed', !cb.checked);
          });
        };
        cb.addEventListener('change', setState);
        setState(); // initial
      };

      // Master toggle controls all .penalty-field
      bindToggle('penaltyEnabled', '.penalty-field');
      // Individual toggles
      bindToggle('moveoutEnabled', '.moveout-field');
      bindToggle('roomChangeEnabled', '.roomchange-field');
    });
  </script>
</body>
</html>
