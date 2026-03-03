<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #fee2e2;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .header h2 {
            color: #b91c1c;
            margin: 0;
        }

        .content p {
            font-size: 15px;
            color: #374151;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .details {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .details p {
            margin: 8px 0;
        }

        .details strong {
            color: #111827;
            display: inline-block;
            min-width: 120px;
        }

        .btn-container {
            text-align: center;
            margin-top: 30px;
        }

        .btn {
            background-color: #b91c1c;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Hore! Ada Booking Baru Masuk 📸</h2>
        </div>

        <div class="content">
            <p>Halo Admin Alineas Studio,</p>
            <p>Sistem baru saja menerima pesanan masuk yang sedang menunggu pembayaran DP. Berikut adalah rinciannya:
            </p>

            <div class="details">
                <p><strong>Kode Booking:</strong> {{ $booking->booking_code }}</p>
                <p><strong>Nama Klien:</strong> {{ $booking->user->name }}</p>
                <p><strong>No. HP/WA:</strong> {{ $booking->user->phone ?? '-' }}</p>
                <p><strong>Paket:</strong> {{ $booking->package->name }}</p>
                <p><strong>Tanggal:</strong>
                    {{ \Carbon\Carbon::parse($booking->start_time)->timezone('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </p>
                <p><strong>Jam:</strong>
                    {{ \Carbon\Carbon::parse($booking->start_time)->timezone('Asia/Jakarta')->format('H:i') }} -
                    {{ \Carbon\Carbon::parse($booking->end_time)->timezone('Asia/Jakarta')->format('H:i') }} WIB</p>
                <p><strong>Total Harga:</strong> Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
            </div>

            <p>Sistem akan otomatis membatalkan booking ini jika pelanggan tidak membayar DP dalam waktu 15 menit.</p>

            <div class="btn-container">
                <a href="{{ url('/admin/dashboard') }}" class="btn">Buka Dashboard Admin</a>
            </div>
        </div>
    </div>
</body>

</html>
