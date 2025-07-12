<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Penghuni</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;500&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="/style/font.css">
  @vite('resources/css/app.css')
</head>
<body class="use-poppins-normal bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen w-full">
    {{-- Sidebar --}}
    @include('components.sidebar-landboard')

    <div id="main-content" class="main-content p-6 md:pt-4 w-full">
      <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
        <p><strong class="use-poppins">Cari Penghuni</strong></p>
        <p class="text-[14px]">Temukan penghuni hanya dengan mengetikan identitas mereka.</p>
      </div>
      @if(session('success'))
        <div class="mt-6 px-4 py-2 rounded-lg bg-emerald-100 text-emerald-700 border border-emerald-300">
          <i class="bi bi-check-circle-fill mr-2"></i> {{ session('success') }}
        </div>
      @endif

      <div class="text-gray-600 mt-6 search-input-wrapper mb-6 bg-white rounded-xl shadow-md px-3 py-1 flex items-center">
        <i class="bi bi-search search-icon text-gray-500 ml-2"></i>
        <form id="room-search-form" method="GET" class="flex-grow flex items-center relative">
          <input type="text" name="search" placeholder="Cari nama atau username"
                 value="{{ request('search') }}" 
                 class="w-full border-none outline-none bg-transparent pl-4">
          <button type="button" class="filter-sort-toggle-btn text-black text-2xl cursor-pointer p-1 rounded-full transition duration-200 ease-in-out hover:bg-gray-100" onclick="toggleFilterSortDropdown(this)">
            <i class="bi bi-sliders"></i>
          </button>
          <div class="filter-sort-dropdown hidden absolute right-0 top-full z-10 bg-white rounded-lg shadow-lg mt-2 p-4 w-64">
            <div class="mb-4">
              <label for="status-filter" class="block text-gray-700 font-medium text-sm mb-1">Status:</label>
              <select id="status-filter" name="status" onchange="document.getElementById('room-search-form').submit()"
                      class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="">Semua</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
              </select>
            </div>
            <div>
              <label for="sort-filter" class="block text-gray-700 font-medium text-sm mb-1">Urutkan:</label>
              <select id="sort-filter" name="sort" onchange="document.getElementById('room-search-form').submit()"
                      class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <option value="">-- Pilih --</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                <option value="username_asc" {{ request('sort') == 'username_asc' ? 'selected' : '' }}>Username A-Z</option>
                <option value="username_desc" {{ request('sort') == 'username_desc' ? 'selected' : '' }}>Username Z-A</option>
              </select>
            </div>
          </div>
        </form>
      </div>

      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
      @if ($tenants->isEmpty())
        <div class="text-center text-gray-500 mt-4">
          {{-- Desain --}}
            @if(request('search'))
                Tidak ada tenant dengan kata kunci "<strong>{{ request('search') }}</strong>"
            @else
                Belum ada data tenant yang tersedia.
            @endif
        </div>
      @else
        @foreach($tenants as $index => $tenant)
            <div class="bg-white shadow-md rounded-xl p-6 border border-[#e3dcd6] relative">
              <div class="flex items-start gap-4">
                <img src="{{asset('storage/'. $tenant->selfie_photo) ?? '/assets/default-avatar.png' }}" alt="Foto Tenant" class="w-24 h-24 rounded-full object-cover border border-gray-300">
                <div>
                  <h2 class="text-md use-poppins mb-1">{{ $tenant->name ?? 'Belum diisi' }}</h2>
                  <p class="text-sm use-poppins-normal text-gray-500 mb-1"><strong>Username:</strong> {{ $tenant->account->username }}</p>
                  <p class="text-sm use-poppins-normal text-gray-500 mb-1"><strong>No Kamar:</strong> {{ $tenant->room->room_number ?? '-' }}</p>
                  <p class="text-sm use-poppins-normal text-gray-500 sm:text-sm {{ $tenant->status === 'aktif' ? 'text-green-600' : 'text-red-600' }}"><strong class="text-gray-500">Status:</strong> {{ ucfirst($tenant->status ?? 'aktif') }}</p>
                </div>
                <div class="ml-auto relative">
                  <button onclick="toggleDropdown(this)" class="text-gray-600 hover:text-black focus:outline-none">
                    <i class="bi bi-three-dots-vertical"></i>
                  </button>
                  <div class="absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded-md shadow-lg z-10 hidden">
                    <a href="{{ route('landboard.tenants.show', $tenant->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Detail</a>
                    @if($tenant->status === 'nonaktif')
                      <a href="{{ route('landboard.tenants.reactivate.form', $tenant->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Aktifkan</a>
                    @else
                      <a href="{{ route('landboard.tenants.edit', $tenant->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                      <form action="{{ route('landboard.tenants.deactivate', $tenant->id) }}" method="POST" onsubmit="return confirm('Yakin nonaktifkan tenant ini?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Nonaktifkan</button>
                      </form>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      const sidebar = document.getElementById('sidebar');
      const mainContent = document.getElementById('main-content');
      const toggleBtn = document.getElementById('toggleSidebar');
      const filterToggleBtn = document.querySelector('.filter-sort-toggle-btn');
      const filterDropdown = document.querySelector('.filter-sort-dropdown');
      const statusSelect = document.getElementById('status-filter');
      const sortSelect = document.getElementById('sort-filter');
      const searchForm = document.getElementById('room-search-form');

      // Create overlay for mobile
      const overlay = document.createElement('div');
      overlay.className = 'mobile-overlay';
      overlay.id = 'mobile-overlay';
      document.body.appendChild(overlay);

      // Sidebar functions
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

      if (filterToggleBtn) {
        filterToggleBtn.addEventListener('click', function(e) {
          e.stopPropagation();
          filterDropdown.classList.toggle('hidden');
        });
      }

      if (statusSelect) {
        statusSelect.addEventListener('change', function() {
          setTimeout(() => {
            searchForm.submit();
          }, 100);
        });
      }

      if (sortSelect) {
        sortSelect.addEventListener('change', function() {
          setTimeout(() => {
            searchForm.submit();
          }, 100);
        });
      }

      document.addEventListener('click', function(event) {
        if (filterDropdown && !filterDropdown.contains(event.target) && !filterToggleBtn.contains(event.target)) {
          filterDropdown.classList.add('hidden');
        }

        document.querySelectorAll('[onclick^="toggleDropdown"]').forEach(btn => {
          const menu = btn.nextElementSibling;
          if (menu && !btn.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.add('hidden');
          }
        });
      });

      if (filterDropdown) {
        filterDropdown.addEventListener('click', function(e) {
          e.stopPropagation();
        });
      }

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
    });

    function toggleDropdown(button) {
      const menu = button.nextElementSibling;
      document.querySelectorAll('[onclick^="toggleDropdown"]').forEach(btn => {
        if (btn !== button) {
          const otherMenu = btn.nextElementSibling;
          if (otherMenu) {
            otherMenu.classList.add('hidden');
          }
        }
      });
      menu.classList.toggle('hidden');
    }
  </script>
</body>
</html>
