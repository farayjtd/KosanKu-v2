@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KosanKu - Dashboard Landboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">

  @include('components.sidebar-landboard')

  <div class="ml-[240px] w-[calc(100%-240px)] p-6 md:pt-4">
    <p class="text-xl p-4 rounded-xl text-left text-white bg-[#31c594]">Selamat datang kembali, <strong class="use-poppins">{{ Auth::user()->landboard->name }}</strong></p>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
      <div class="bg-[#F7F9F4] p-6 rounded-xl shadow">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold text-black mb-2">Total Penghuni</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $totalTenants }}</p>
          </div>
          <div class="text-4xl text-black">
            <i class="bi bi-people"></i>
          </div>
        </div>
      </div>

      <div class="bg-[#F7F9F4] p-6 rounded-xl shadow">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold text-black mb-2">Data Kamar</h3>
            <div class="space-y-1">
              <div class="flex justify-between text-sm">
                <span>Total:</span>
                <span class="font-semibold">{{ $totalRooms }}</span>
              </div>
              <div class="flex justify-between text-sm">
                <span>Terisi:</span>
                <span class="font-semibold text-green-600">{{ $occupiedRooms }}</span>
              </div>
              <div class="flex justify-between text-sm">
                <span>Kosong:</span>
                <span class="font-semibold text-red-600">{{ $totalRooms - $occupiedRooms }}</span>
              </div>
            </div>
          </div>
          <div class="text-4xl text-black">
            <i class="bi bi-door-open"></i>
          </div>
        </div>
      </div>

      <div class="bg-[#fffaf6] p-6 rounded-xl shadow">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold text-black mb-2">Data Pemasukan</h3>
            <div class="space-y-1">
              <div class="flex justify-between text-sm">
                <span>Bulan Ini:</span>
                <span class="font-semibold">Rp{{ number_format($monthlyIncome, 0, ',', '.') }}</span>
              </div>
              <div class="flex justify-between text-sm">
                <span>Tahun Ini:</span>
                <span class="font-semibold">Rp{{ number_format($yearlyIncome, 0, ',', '.') }}</span>
              </div>
            </div>
          </div>
          <div class="text-4xl text-black">
            <i class="bi bi-currency-dollar"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
      <div class="bg-white p-4 rounded-xl shadow">
        <h3 class="text-lg font-semibold text-black mb-4">Kalender</h3>
        <div id="calendar" class="text-sm">
          <div class="grid grid-cols-7 gap-1 mb-2">
            <div class="text-center font-semibold text-gray-600 p-1">Min</div>
            <div class="text-center font-semibold text-gray-600 p-1">Sen</div>
            <div class="text-center font-semibold text-gray-600 p-1">Sel</div>
            <div class="text-center font-semibold text-gray-600 p-1">Rab</div>
            <div class="text-center font-semibold text-gray-600 p-1">Kam</div>
            <div class="text-center font-semibold text-gray-600 p-1">Jum</div>
            <div class="text-center font-semibold text-gray-600 p-1">Sab</div>
          </div>
          <div class="grid grid-cols-7 gap-1" id="calendar-dates">
          </div>
        </div>
      </div>

      <div class="bg-white p-4 rounded-xl shadow">
        <h3 class="text-lg font-semibold text-black mb-4">Grafik Pemasukan</h3>
        <div style="height: 200px;">
          <canvas id="incomeChart"></canvas>
        </div>
      </div>
    </div>

    <div class="mt-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white p-6 rounded-xl shadow border-l-4 border-green-500">
          <div class="flex items-center justify-between mb-4">
            <h4 class="text-lg font-semibold text-green-700">Transaksi Terbaru</h4>
            <i class="bi bi-credit-card text-2xl text-green-600"></i>
          </div>
          @if($recentPayments->count() > 0)
            <p class="text-sm text-gray-600 mb-3">Ada {{ $recentPayments->count() }} transaksi terbaru</p>
            <div class="space-y-2">
              @foreach($recentPayments->take(3) as $payment)
                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                  <div>
                    <p class="text-sm font-medium">{{ $payment->tenant->name }}</p>
                    <p class="text-xs text-gray-500">{{ $payment->created_at->format('d M Y') }}</p>
                  </div>
                  <p class="text-sm font-semibold text-green-600">Rp{{ number_format($payment->amount, 0, ',', '.') }}</p>
                </div>
              @endforeach
            </div>
          @else
            <p class="text-sm text-gray-500">Belum ada transaksi terbaru</p>
          @endif
        </div>

        <div class="bg-white p-6 rounded-xl shadow border-l-4 border-blue-500">
          <div class="flex items-center justify-between mb-4">
            <h4 class="text-lg font-semibold text-blue-700">Tenant Baru</h4>
            <i class="bi bi-person-plus text-2xl text-blue-600"></i>
          </div>
          @if($newTenants->count() > 0)
            <p class="text-sm text-gray-600 mb-3">Ada {{ $newTenants->count() }} penghuni baru bergabung</p>
            <div class="space-y-2">
              @foreach($newTenants->take(3) as $tenant)
                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                  <div>
                    <p class="text-sm font-medium">{{ $tenant->name }}</p>
                    <p class="text-xs text-gray-500">Kamar {{ $tenant->room->room_number ?? '-' }}</p>
                  </div>
                  <p class="text-xs text-blue-600">
                  {{ $tenant->rentalHistories->last()?->start_date
                    ? Carbon::parse($tenant->rentalHistories->last()->start_date)->format('d M Y')
                    : '-' }}
                  </p>
                </div>
              @endforeach
            </div>
          @else
            <p class="text-sm text-gray-500">Belum ada penghuni baru</p>
          @endif
        </div>

        <div class="bg-white p-6 rounded-xl shadow border-l-4 border-red-500">
          <div class="flex items-center justify-between mb-4">
            <h4 class="text-lg font-semibold text-red-700">Tagihan Belum Lunas</h4>
            <i class="bi bi-exclamation-triangle text-2xl text-red-600"></i>
          </div>
          @if($unpaidPayments->count() > 0)
            <p class="text-sm text-gray-600 mb-3">Ada {{ $unpaidPayments->count() }} tagihan yang belum lunas</p>
            <div class="space-y-2">
              @foreach($unpaidPayments->take(3) as $bill)
                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                  <div>
                    <p class="text-sm font-medium">{{ $bill->tenant->name }}</p>
                    <p class="text-xs text-gray-500">{{ Carbon::parse($bill->due_date)->format('d M Y') }}</p>
                  </div>
                  <p class="text-sm font-semibold text-red-600">Rp{{ number_format($bill->amount, 0, ',', '.') }}</p>
                </div>
              @endforeach
            </div>
          @else
            <p class="text-sm text-gray-500">Tidak ada tagihan belum lunas</p>
          @endif
        </div>

      </div>
    </div>
  </div>

  <script>
    function generateCalendar() {
      const now = new Date();
      const year = now.getFullYear();
      const month = now.getMonth();
      const today = now.getDate();
      
      const firstDay = new Date(year, month, 1).getDay();
      const daysInMonth = new Date(year, month + 1, 0).getDate();
      
      const calendarDates = document.getElementById('calendar-dates');
      calendarDates.innerHTML = '';
      
      for (let i = 0; i < firstDay; i++) {
        calendarDates.innerHTML += '<div class="p-1"></div>';
      }
      
      for (let day = 1; day <= daysInMonth; day++) {
        const isToday = day === today;
        const className = isToday 
          ? 'text-center p-1 bg-[#31c594] text-white rounded-full cursor-pointer text-xs' 
          : 'text-center p-1 hover:bg-gray-100 rounded-full cursor-pointer text-xs';
        calendarDates.innerHTML += `<div class="${className}">${day}</div>`;
      }
    }
    
    generateCalendar();

    const ctx = document.getElementById('incomeChart');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: {!! json_encode($monthlyLabels) !!},
        datasets: [{
          label: 'Pemasukan',
          data: {!! json_encode($monthlyAmounts) !!},
          backgroundColor: '#31c594',
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return 'Rp' + value.toLocaleString('id-ID');
              }
            }
          }
        }
      }
    });
  </script>

</body>
</html>