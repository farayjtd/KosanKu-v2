<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Permintaan Pindah Kamar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  <script src="{{ asset('js/sidebar.js') }}" defer></script>
  <style>
.sidebar.collapsed {
            width: 80px;
        }
        
        .sidebar.collapsed .menu-text {
            display: none;
        }
        
        .sidebar.collapsed .menu-title {
            display: none;
        }
        
        .sidebar.collapsed .group-title {
            display: none;
        }
        
        .sidebar.collapsed .logo-text {
            display: none;
        }
        
        .sidebar.collapsed .menu-item {
            justify-content: center;
            padding: 0.45rem;
        }
        
        .sidebar.collapsed .profile-btn,
        .sidebar.collapsed .logout-btn {
            padding: 0.45rem;
            justify-content: center;
            min-height: 48px;
        }
        
        .sidebar.collapsed .profile-btn .btn-text,
        .sidebar.collapsed .logout-btn .btn-text {
            display: none;
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
            
            .sidebar .menu-text {
                display: none;
            }
            
            .sidebar .menu-title {
                display: none;
            }
            
            .sidebar .group-title {
                display: none;
            }
            
            .sidebar .logo-text {
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
            
            .sidebar .profile-btn .btn-text,
            .sidebar .logout-btn .btn-text {
                display: none;
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
              
              /* Overlay untuk mobile */
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
<body class="bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-landboard')

    <div id="main-content" class="main-content">
      <div class="card">
        <h2>Permintaan Pindah Kamar</h2>

        @if (session('success'))
          <div class="success">{{ session('success') }}</div>
        @elseif (session('error'))
          <div class="error">{{ session('error') }}</div>
        @endif

        <table>
          <thead>
            <tr>
              <th>Username</th>
              <th>Tenant</th>
              <th>Dari</th>
              <th>Ke</th>
              <th>Refund</th>
              <th>Catatan</th>
              <th>Status</th>
              <th>Tindakan</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($requests as $request)
              <tr>
                <td>{{ $request->tenant->account->username }}</td>
                <td>{{ $request->tenant->name ?: 'Belum diisi' }}</td>
                <td>{{ $request->currentRoom->room_number }}</td>
                <td>{{ $request->newRoom->room_number }}</td>
                <td>Rp{{ number_format($request->manual_refund, 0, ',', '.') }}</td>
                <td class="note">{{ $request->note ?: '-' }}</td>
                <td>
                  <span class="status {{ $request->status }}">
                    @switch($request->status)
                      @case('pending') Menunggu @break
                      @case('approved') Disetujui @break
                      @case('rejected') Ditolak @break
                    @endswitch
                  </span>
                </td>
                <td>
                  @if ($request->status === 'pending')
                    <form method="POST" action="{{ route('landboard.room-transfer.handle', $request->id) }}">
                      @csrf
                      <textarea name="note" placeholder="Catatan persetujuan atau penolakan" required></textarea>
                      <button type="submit" name="action_type" value="approve">Setujui</button>
                      <button type="submit" name="action_type" value="reject" class="btn-reject">Tolak</button>
                    </form>
                  @else
                    <span class="note">-</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" style="text-align: center; color: #888;">Tidak ada permintaan pindah kamar.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
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
});
</script>
</body>
</html>
