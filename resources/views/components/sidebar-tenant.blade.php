<style>
    .sidebar.collapsed {
        width: 70px;
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
        
    .logout-btn {
        padding: 0.45rem;
        justify-content: center;
    }

    .sidebar.collapsed .profile-btn,
    .sidebar.collapsed .logout-btn {
        padding: 0.45rem;
        justify-content: center;
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
        margin-left: 230px;
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
            margin-left: 0px;
            width: calc(100% - 80px);
        }
            
        .main-content.collapsed {
            margin-left: 70px;
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
<div id="sidebar" class="sidebar bg-white w-[240px] h-screen fixed flex flex-col overflow-y-hidden top-0 left-0 z-50 shadow-xl px-4 py-6 transition-all duration-300">
    @php
        $user = auth()->user();
        $navStyle = 'menu-item flex items-center gap-3 px-4 py-1 rounded-lg text-sm font-medium transition hover:bg-emerald-100 hover:text-emerald-600';
        $hoverScript = 'onmouseover="this.style.background=\'#ddb892\'" onmouseout="this.style.background=\'transparent\'"';
    @endphp

    {{-- Foto Profil --}}
    <!-- <div style="text-align: center; margin-bottom: 24px;">
        <img
            src="{{ $user && $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}"
            alt="Avatar"
            style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; border: 2px solid #b08968;"
        >
        <p style="margin-top: 10px; font-weight: 600; color: #5e503f;">
            {{ $user->username ?? 'Tenant' }}
        </p>
    </div> -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="logo-text text-xl font-bold text-emerald-600">KosanKu</h1>
        <button id="toggleSidebar" class="text-xl text-slate-600 hover:text-emerald-600">
            <i class="bi bi-list ml-2"></i>
        </button>
    </div>
    {{-- Navigasi --}}
    <nav class="flex-1 w-full space-y-6">
        <div>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('tenant.dashboard.index') }}" 
                        class="{{ $navStyle }}">
                        <i class="bi bi-house-door text-lg"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>
        <div>
        <h2 class="group-title text-xs text-slate-500 font-semibold uppercase tracking-wide px-2 mb-2">Management</h2>
            <ul>
                <li>
                    <a href="{{ route('tenant.room-transfer.form') }}" 
                        class="{{ $navStyle }}">
                        <i class="bi bi-arrow-left-right text-lg"></i> 
                        <span class="menu-text">Pindah Kamar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('tenant.rental.history') }}" 
                        class="{{ $navStyle }}">
                        <i class="bi bi-journal-text text-lg"></i> 
                        <span class="menu-text">Riwayat Sewa</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('tenant.renewal.direct') }}" 
                        class="{{ $navStyle }}">
                        <i class="bi bi-clock text-lg"></i> 
                        <span class="menu-text">Perpanjangan Sewa</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('tenant.payment.list') }}" 
                        class="{{ $navStyle }}">
                        <i class="bi bi-cash-stack text-lg"></i> 
                        <span class="menu-text">Tagihan Pembayaran</span>
                    </a>
                </li>

            </ul>
        </div>
        <div>
            <h2 class="group-title text-xs text-slate-500 font-semibold uppercase tracking-wide px-2 mb-2">Pengaturan</h2>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('tenant.profile.edit') }}" 
                        class="{{ $navStyle }}">
                        <i class="bi bi-person-gear text-lg"></i> 
                        <span class="menu-text">Pengaturan Profil</span>
                    </a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button
                            type="submit"
                            class="cursor-pointer logout-btn w-full flex items-center gap-3 px-4 py-1 rounded-lg text-sm font-medium transition bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-700">
                            <i class="bi bi-box-arrow-right text-lg"></i> 
                            <span class="btn-text">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
</div>
