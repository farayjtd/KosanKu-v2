<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Permintaan Pindah Kamar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  @vite('resources/css/app.css')
  <style>[x-cloak]{display:none}
    .sidebar.collapsed {
      width: 80px;
    }

    .sidebar.collapsed .menu-text,
    .sidebar.collapsed .menu-title,
    .sidebar.collapsed .group-title,
    .sidebar.collapsed .logo-text,
    .sidebar.collapsed .profile-btn .btn-text,
    .sidebar.collapsed .logout-btn .btn-text {
      display: none;
    }

    .sidebar.collapsed .menu-item,
    .sidebar.collapsed .profile-btn,
    .sidebar.collapsed .logout-btn {
      justify-content: center;
      padding: 0.45rem;
      min-height: 48px;
    }
    .sidebar.collapsed .menu-item {
        min-height: unset;
    }

    .sidebar-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      padding: 1rem;
    }

    .sidebar-footer {
      margin-top: auto;
      padding: 1rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .main-content {
      margin-left: 240px;
      width: calc(100% - 240px);
      transition: all 0.3s ease;
      min-height: 100vh;
    }

    .main-content.collapsed {
      margin-left: 80px;
      width: calc(100% - 80px);
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 80px;
      }
      .sidebar .menu-text,
      .sidebar .menu-title,
      .sidebar .group-title,
      .sidebar .logo-text,
      .sidebar .profile-btn .btn-text,
      .sidebar .logout-btn .btn-text {
        display: none;
      }
      .sidebar .menu-item {
        justify-content: center;
        padding: 0.75rem;
      }
      .sidebar .profile-btn,
      .sidebar .logout-btn {
        padding: 0.75rem;
        justify-content: center;
        min-height: 48px;
      }
      .main-content {
        margin-left: 80px;
        width: calc(100% - 80px);
      }
      .main-content.collapsed {
        margin-left: 80px;
        width: calc(100% - 80px);
      }
    }

    @media (max-width: 640px) {
      .sidebar {
        width: 60px;
      }
      .sidebar .menu-item {
        padding: 0.5rem;
      }
      .sidebar .profile-btn,
      .sidebar .logout-btn {
        padding: 0.5rem;
        min-height: 40px;
      }
      .main-content {
        margin-left: 60px;
        width: calc(100% - 60px);
      }
      .main-content.collapsed {
        margin-left: 60px;
        width: calc(100% - 60px);
      }
      @media (max-width: 768px) {
        .sidebar.mobile-expanded {
          width: 100vw !important;
          z-index: 60;
        }
        .sidebar.mobile-expanded .menu-text,
        .sidebar.mobile-expanded .menu-title,
        .sidebar.mobile-expanded .group-title,
        .sidebar.mobile-expanded .logo-text,
        .sidebar.mobile-expanded .btn-text {
          display: block !important;
        }
        .sidebar.mobile-expanded .menu-item {
          justify-content: flex-start !important;
          padding: 0.5rem 1rem !important;
        }
        .sidebar.mobile-expanded .profile-btn,
        .sidebar.mobile-expanded .logout-btn {
          justify-content: center !important;
          padding: 0.5rem 1rem !important;
        }
        .mobile-overlay {
          display: none;
          position: fixed;
          top: 0;
          left: 0;
          width: 100vw;
          height: 100vh;
          background: rgba(0, 0, 0, 0.5);
          z-index: 50;
        }
        .mobile-overlay.active {
          display: block;
        }
      }
    }
  </style>
</head>
<body class="bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')"">
  <div id="wrapper" class="flex min-h-screen w-full">
    {{-- Sidebar --}}
    @include('components.sidebar-landboard')

    {{-- Main Content --}}
    <div id="main-content" class="main-content transition-all duration-300 ease-in-out flex-1 md:ml-[240px] p-4 md:p-6">
      <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6">Permintaan Pindah Kamar</h2>

        {{-- Flash Messages --}}
        @if (session('success'))
          <div class="mb-4 px-4 py-2 rounded-lg bg-emerald-100 text-emerald-700 border border-emerald-300">
            {{ session('success') }}
          </div>
        @elseif (session('error'))
          <div class="mb-4 px-4 py-2 rounded-lg bg-red-100 text-red-700 border border-red-300">
            {{ session('error') }}
          </div>
        @endif

        <div class="overflow-x-auto rounded-lg border border-gray-200">
          <table class="w-full border-collapse text-sm">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs tracking-wider">
              <tr>
                <th class="px-4 py-3 whitespace-nowrap">Username</th>
                <th class="px-4 py-3">Tenant</th>
                <th class="px-4 py-3">Dari</th>
                <th class="px-4 py-3">Ke</th>
                <th class="px-4 py-3">Refund</th>
                <th class="px-4 py-3">Catatan</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Tindakan</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              @forelse($requests as $request)
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-3 whitespace-nowrap">{{ $request->tenant->account->username }}</td>
                  <td class="px-4 py-3">{{ $request->tenant->name ?: 'Belum diisi' }}</td>
                  <td class="px-4 py-3">{{ $request->currentRoom->room_number }}</td>
                  <td class="px-4 py-3">{{ $request->newRoom->room_number }}</td>
                  <td class="px-4 py-3 whitespace-nowrap">Rp{{ number_format($request->manual_refund, 0, ',', '.') }}</td>
                  <td class="px-4 py-3 max-w-xs truncate" title="{{ $request->note }}">{{ $request->note ?: '-' }}</td>
                  <td class="px-4 py-3">
                    @switch($request->status)
                      @case('pending')
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-600 rounded">Menunggu</span>
                      @break
                      @case('approved')
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-600 rounded">Disetujui</span>
                      @break
                      @case('rejected')
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-red-100 text-red-600 rounded">Ditolak</span>
                      @break
                    @endswitch
                  </td>
                  <td class="px-4 py-3">
                    @if($request->status === 'pending')
                      <form method="POST" action="{{ route('landboard.room-transfer.handle', $request->id) }}" class="space-y-2">
                        @csrf
                        <textarea name="note" rows="2" class="w-full border rounded-lg px-2 py-1 text-xs focus:ring-emerald-400" placeholder="Catatan" required></textarea>
                        <div class="flex gap-2 text-xs">
                          <button type="submit" name="action_type" value="approve" class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white py-1 rounded">Setujui</button>
                          <button type="submit" name="action_type" value="reject" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-1 rounded">Tolak</button>
                        </div>
                      </form>
                    @else
                      <span class="text-gray-400">-</span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center text-gray-500 py-6">Tidak ada permintaan pindah kamar.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
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

    document.addEventListener('click', function(event) {
        if (!event.target.closest('.search-input-wrapper')) {
            const filterSortDropdown = document.querySelector('.filter-sort-dropdown');
            if (filterSortDropdown && !filterSortDropdown.classList.contains('hidden')) {
                filterSortDropdown.classList.add('hidden');
            }
        }
    });
  });
  </script>
</body>
</html>