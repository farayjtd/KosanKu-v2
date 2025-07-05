@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pindah Kamar</title>
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
      max-width: 800px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
      margin: auto;
    }

    h2 {
      margin-top: 0;
      color: #1e293b;
    }

    .info {
      background: #f1f5f9;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      color: #334155;
      font-size: 14px;
    }

    .success {
      background: #dcfce7;
      color: #15803d;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
    }

    .error {
      background: #fee2e2;
      color: #b91c1c;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
    }

    .status-pending {
      color: #f59e0b;
    }

    .status-approved {
      color: #16a34a;
    }

    .status-rejected {
      color: #dc2626;
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 14px;
      margin-bottom: 6px;
      color: #334155;
    }

    select, button {
      width: 100%;
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 14px;
      border: 1px solid #cbd5e1;
    }

    button {
      background-color: #2563eb;
      color: white;
      margin-top: 20px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.2s ease-in-out;
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
      <h2>Pindah Kamar</h2>

      {{-- Flash messages --}}
      @if (session('success'))
        <div class="success">{{ session('success') }}</div>
      @endif

      @if (session('error'))
        <div class="error">{{ session('error') }}</div>
      @endif

      {{-- Info Kamar Saat Ini --}}
      <div class="info">
        <p style="font-size: 14px; color: #64748b;">
          Setelah pindah, masa sewa kamar baru akan dimulai selama 1 bulan. Anda bisa memperpanjang kembali sesuai kebutuhan.
        </p>
        <p><strong>Kamar Saat Ini:</strong> {{ $currentHistory->room->room_number }}</p>
        <p><strong>Sisa Hari:</strong> {{ $daysLeft }} hari</p>
        <p><strong>Denda Pindah Kamar:</strong> Rp{{ number_format($currentHistory->room->landboard->room_change_penalty_amount ?? 0, 0, ',', '.') }}</p>
        <p><strong>Estimasi Refund Manual:</strong> Rp{{ number_format($refundAmount, 0, ',', '.') }}</p>
      </div>

      {{-- Status Permintaan Terakhir --}}
      @isset($latestRequest)
        <div class="info">
          <p><strong>Status Permintaan Sebelumnya:</strong>
            @if ($latestRequest->status === 'pending')
              <span class="status-pending">Menunggu Persetujuan</span>
            @elseif ($latestRequest->status === 'approved')
              <span class="status-approved">Disetujui</span>
            @elseif ($latestRequest->status === 'rejected')
              <span class="status-rejected">Ditolak</span>
            @endif
          </p>

          @if ($latestRequest->note)
            <p><strong>Catatan:</strong> {{ $latestRequest->note }}</p>
          @endif
        </div>
      @endisset

      {{-- Form Pengajuan --}}
      @if ($canTransfer)
        @if (!isset($latestRequest) || $latestRequest->status !== 'pending')
          <form method="POST" action="{{ route('tenant.room-transfer.process') }}">
            @csrf

            <label for="room_id">Pilih Kamar Baru</label>
            <select name="room_id" id="room_id" required>
              <option value="">-- Pilih Kamar --</option>
              @foreach ($availableRooms as $room)
                <option value="{{ $room->id }}">
                  {{ $room->room_number }} - Rp{{ number_format($room->price, 0, ',', '.') }} / bulan
                </option>
              @endforeach
            </select>

            <button type="submit">Ajukan Permintaan</button>
          </form>
        @else
          <div class="info">
            <p>Anda sudah mengajukan permintaan pindah kamar dan sedang menunggu persetujuan.</p>
          </div>
        @endif
      @else
        <div class="error">
          💰 Anda belum membayar tagihan terakhir. Silakan selesaikan pembayaran terlebih dahulu sebelum mengajukan pindah kamar.
        </div>
      @endif

    </div>
  </div>

</body>
</html>
