<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tagihan Pembayaran</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/style/font.css">
  @vite('resources/css/app.css')
</head>
<body class="bg-cover bg-no-repeat bg-center" style="background-image: url('/assets/auth.png')">
  <div id="wrapper" class="flex min-h-screen">
    @include('components.sidebar-tenant')

    <div id="main-content" class="main-content p-6 md:pt-4 w-full">
      <div class="mt-6 relative max-w-screen mx-auto bg-white rounded-xl shadow p-6 pt-20">
        <div class="absolute -top-5 left-0 bg-[#31c594] text-white px-6 py-3 rounded-bl-4xl rounded-tr-4xl z-10">
          <h2 class="font-poppins text-base md:text-lg font-semibold">Tagihan Pembayaran</h2>
        </div>

        @if($payments->isEmpty())
          <div class="text-center py-16 text-slate-400 text-base">
            Belum ada tagihan untuk saat ini.
          </div>
        @else
          <div class="overflow-x-auto">
            <table class="w-full table-auto text-sm text-slate-700">
              <thead class="bg-slate-100 text-slate-700 uppercase text-xs tracking-wider">
                <tr>
                  <th class="px-4 py-3 text-left">Kamar</th>
                  <th class="px-4 py-3 text-left">Jumlah</th>
                  <th class="px-4 py-3 text-left">Jatuh Tempo</th>
                  <th class="px-4 py-3 text-left">Status</th>
                  <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($payments as $payment)
                  @php
                    $isPaid = $payment->status === 'paid';
                    $badgeClass = $isPaid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-700';
                    $badgeText = $isPaid ? 'Sudah Dibayar' : 'Belum Dibayar';
                  @endphp
                  <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-3 border-b">{{ $payment->rentalHistory->room->room_number ?? '-' }}</td>
                    <td class="px-4 py-3 border-b">Rp{{ number_format($payment->total_amount ?? $payment->amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 border-b">{{ \Carbon\Carbon::parse($payment->due_date)->format('d M Y') }}</td>
                    <td class="px-4 py-3 border-b">
                      <span class="inline-block px-2 py-1 text-xs font-medium rounded {{ $badgeClass }}">
                        {{ $badgeText }}
                      </span>
                    </td>
                    <td class="px-4 py-3 border-b">
                      @if(!$isPaid)
                        <button class="bg-slate-600 text-white px-3 py-1 rounded hover:bg-slate-700 text-xs" onclick="toggleMethodForm({{ $payment->id }})">
                          Bayar
                        </button>

                        <div class="method-form mt-2 bg-slate-50 p-4 rounded-lg hidden" id="method-form-{{ $payment->id }}">
                          <form action="{{ route('tenant.payment.createInvoice', ['rentalId' => $payment->rental_history_id]) }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="rental_id" value="{{ $payment->rental_history_id }}">

                            <label class="block text-sm font-medium text-slate-700">Pilih Metode Pembayaran:</label>
                            <div class="space-y-1">
                              @foreach($channels as $channel)
                                <label class="flex items-center space-x-2 text-sm text-slate-700">
                                  <input type="radio" name="payment_method" value="{{ $channel['code'] }}" required>
                                  <span>{{ $channel['name'] }} ({{ $channel['group'] }})</span>
                                </label>
                              @endforeach
                            </div>

                            <button type="submit" class="bg-slate-600 hover:bg-slate-700 text-white text-sm px-4 py-2 rounded">
                              Lanjutkan Pembayaran
                            </button>
                          </form>
                        </div>
                      @else
                        <span class="text-slate-400 italic text-sm">-</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>

  <script>
    function toggleMethodForm(id) {
      document.querySelectorAll('.method-form').forEach(form => form.classList.add('hidden'));
      const target = document.getElementById('method-form-' + id);
      if (target) target.classList.remove('hidden');
    }
  </script>
</body>
</html>
