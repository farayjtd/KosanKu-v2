@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perpanjangan Sewa</title>
  <style>
    body {
      margin: 0;
      font-family: sans-serif;
      display: flex;
      background: #f1f5f9;
    }

    .main-content {
      flex: 1;
      padding: 30px;
      background: #f8fafc;
    }

    .card {
      background: white;
      padding: 28px;
      border-radius: 12px;
      max-width: 700px;
      margin: auto;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    h2 {
      margin-top: 0;
      color: #1e293b;
    }

    .back-link {
      display: inline-block;
      margin-bottom: 16px;
      color: #3b82f6;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }

    .info {
      background: #f1f5f9;
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 14px;
      color: #475569;
    }

    .error-box {
      background: #fee2e2;
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      color: #b91c1c;
      font-size: 14px;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    label {
      margin-bottom: 6px;
      font-weight: bold;
      color: #475569;
    }

    select, button {
      padding: 10px 14px;
      font-size: 15px;
      border-radius: 8px;
      border: 1px solid #cbd5e1;
      margin-bottom: 18px;
    }

    button {
      background-color: #2563eb;
      color: white;
      border: none;
      cursor: pointer;
      transition: background 0.2s ease;
    }

    button:hover {
      background-color: #1e40af;
    }
  </style>
</head>
<body>

  @include('components.sidebar-tenant')

  <div class="main-content">
    <div class="card">
      <h2>Perpanjangan Sewa</h2>

      <a href="{{ route('tenant.rental.history') }}" class="back-link">← Kembali ke Riwayat Sewa</a>

      @php
        $room = $history->room ?? null;
        $landboard = $room->landboard ?? null;
        $startDate = Carbon::parse($history->start_date ?? now());
        $endDate = Carbon::parse($history->end_date ?? now());
        $decisionDays = $landboard->decision_days_before_end ?? 5;
        $limitDate = (clone $endDate)->subDays($decisionDays);
        $canRenew = $can_renew ?? false;
        $paymentStatus = $history->payment->status ?? null;

        $now = now();
        $remainingTime = $now->lt($endDate)
          ? $now->diff($endDate)->format('%d hari %h jam')
          : 'Masa sewa telah berakhir';
      @endphp

      <div class="info">
        <strong>Kamar:</strong> {{ $room->room_number ?? '-' }}<br>
        <strong>Tanggal Sewa:</strong> {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}<br>
        <strong>Perpanjangan Dibuka:</strong> {{ $limitDate->format('d M Y') }} ({{ $decisionDays }} hari sebelum berakhir)
      </div>

      {{-- Flash error --}}
      @if (session('error'))
        <div class="error-box">{{ session('error') }}</div>
      @endif

      {{-- Validation errors --}}
      @if ($errors->any())
        <div class="error-box">
          <strong>Terjadi kesalahan:</strong>
          <ul style="margin: 8px 0 0 18px;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Belum lunas --}}
      @if ($paymentStatus !== 'paid')
        <div class="error-box">
          💰 Anda harus menyelesaikan pembayaran sewa saat ini terlebih dahulu sebelum mengajukan perpanjangan.
        </div>
      @elseif (!$canRenew)
        <div class="info">
          ⏳ Sisa {{ $remainingTime }}, sewa berakhir pada {{ $endDate->format('d M Y') }}.
        </div>
      @else
        {{-- Form perpanjangan --}}
        <form action="{{ route('tenant.renewal.store', $history->id) }}" method="POST">
          @csrf

          <label for="duration">Durasi Perpanjangan</label>
          <select name="duration" id="duration" required>
            <option value="">-- Pilih Durasi --</option>
            <option value="0.1" {{ old('duration') == 0.1 ? 'selected' : '' }}>5 Hari (uji coba)</option>
            <option value="1" {{ old('duration') == 1 ? 'selected' : '' }}>1 Bulan</option>
            <option value="3" {{ old('duration') == 3 ? 'selected' : '' }}>3 Bulan</option>
            <option value="6" {{ old('duration') == 6 ? 'selected' : '' }}>6 Bulan</option>
            <option value="12" {{ old('duration') == 12 ? 'selected' : '' }}>12 Bulan</option>
          </select>

          <button type="submit">Ajukan Perpanjangan</button>
        </form>
      @endif
    </div>
  </div>

</body>
</html>
