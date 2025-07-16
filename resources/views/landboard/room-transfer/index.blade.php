
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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  @vite('resources/css/app.css')
  <style>
    .financial-table {
      width: 100%;
      border-collapse: collapse;
    }

    .financial-table th,
    .financial-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #e5e7eb;
    }

    .financial-table th {
      background-color: #f9fafb;
      font-weight: 600;
      color: #374151;
      font-size: 14px;
    }

    .financial-table td {
      font-size: 14px;
      color: #6b7280;
    }

    .financial-table tr:hover {
      background-color: #f9fafb;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
      .financial-table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
      }

      .financial-table thead,
      .financial-table tbody,
      .financial-table th,
      .financial-table td,
      .financial-table tr {
        display: block;
      }

      .financial-table thead tr {
        position: absolute;
        top: -9999px;
        left: -9999px;
      }

      .financial-table tr {
        border: 1px solid #e5e7eb;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 8px;
        background-color: white;
      }

      .financial-table td {
        border: none;
        position: relative;
        padding: 6px 6px 6px 50%;
        text-align: left;
      }

      .financial-table td:before {
        content: attr(data-label);
        position: absolute;
        left: 6px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
        font-weight: 600;
        color: #374151;
      }
    }
  </style>
</head>
<body class="use-poppins-normal bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen w-full">
    {{-- Sidebar --}}
    @include('components.sidebar-landboard')

    {{-- Main Content --}}
    <div id="main-content" class="main-content transition-all duration-300 ease-in-out flex-1 md:ml-[240px] p-4 md:p-6">
      <div class="text-xl p-4 rounded-xl text-left text-white bg-gradient-to-r from-[#31c594] to-[#2ba882]">
        <p><strong class="use-poppins">Pengajuan Pindah Kamar</strong></p>
        <p class="text-[14px]">Pantau pengajuan pindah kamar dari penghuni kos.</p>
      </div>
      <div class="relative mt-6">
        <!-- <div class="absolute -top-5 left-0 bg-[#31c594] text-white px-6 py-3 rounded-bl-4xl rounded-tr-4xl z-10">
          <h2 class="use-poppins text-base md:text-lg font-semibold">Riwayat Sewa Penghuni</h2>
        </div> -->
          <div class="bg-white rounded-xl shadow-md ">
            <div class="w-full bg-white rounded-xl shadow-md">
              <div class="p-4">
                @if ($requests->isNotEmpty())
                <div class="overflow-x-auto mb-4">
                  <table class="financial-table">
                    <thead class="md:table-header-group uppercase">
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
                      @foreach($requests as $request)
                        <tr>
                        <td data-label="Username">{{ $request->tenant->account->username }}</td>
                          <td data-label="Tenant">{{ $request->tenant->name ?: 'Belum diisi' }}</td>
                          <td data-label="Dari">{{ $request->currentRoom->room_number }}</td>
                          <td data-label="Ke">{{ $request->newRoom->room_number }}</td>
                          <td data-label="Refund" class="text-[#31c594] font-semibold">Rp{{ number_format($request->manual_refund, 0, ',', '.') }}</td>
                          <td data-label="Catatan" title="{{ $request->note }}">{{ $request->note ?: '-' }}</td>
                          <td data-label="Status">
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
                          <td data-label="Tindakan">
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
                      @endforeach
                    </tbody>
                  </table>
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                  <i class="bi bi-inbox text-4xl mb-2"></i>
                  <p class="text-sm">Tidak ada permintaan pindah kamar.</p>
                </div>
                @endif
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