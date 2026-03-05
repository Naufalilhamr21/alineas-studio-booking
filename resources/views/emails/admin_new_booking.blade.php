<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Booking Baru Masuk</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f9fafb; padding: 20px; margin: 0;">

    <div
        style="background-color: #ffffff; padding: 30px; border-radius: 10px; max-width: 600px; margin: 0 auto; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">

        <div style="text-align: center; border-bottom: 2px solid #fee2e2; padding-bottom: 20px; margin-bottom: 20px;">
            <h2 style="color: #b91c1c; margin: 0; font-size: 24px;">Hore! Ada Booking Baru (LUNAS) 📸</h2>
        </div>

        <div style="color: #374151; font-size: 15px; line-height: 1.6;">
            <p style="margin-top: 0;">Halo Admin Alineas Studio,</p>
            <p>Kabar gembira! Sistem baru saja menerima pesanan masuk dan pelanggan <strong>telah berhasil melakukan
                    pembayaran</strong>. Berikut rincian jadwal yang telah dikunci otomatis:</p>

            <div style="background-color: #f3f4f6; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <p style="margin: 8px 0;">
                    <strong style="color: #111827; display: inline-block; width: 130px;">Kode Booking</strong> :
                    {{ $booking->booking_code }}
                </p>
                <p style="margin: 8px 0;">
                    <strong style="color: #111827; display: inline-block; width: 130px;">Nama Klien</strong> :
                    {{ $booking->user->name }}
                </p>
                <p style="margin: 8px 0;">
                    <strong style="color: #111827; display: inline-block; width: 130px;">No. HP/WA</strong> :
                    {{ $booking->user->phone ?? '-' }}
                </p>
                <p style="margin: 8px 0;">
                    <strong style="color: #111827; display: inline-block; width: 130px;">Paket</strong> :
                    {{ $booking->package->name }}
                </p>
                <p style="margin: 8px 0;">
                    <strong style="color: #111827; display: inline-block; width: 130px;">Tanggal</strong> :
                    {{ \Carbon\Carbon::parse($booking->start_time)->timezone('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </p>
                <p style="margin: 8px 0;">
                    <strong style="color: #111827; display: inline-block; width: 130px;">Jam</strong> :
                    {{ \Carbon\Carbon::parse($booking->start_time)->timezone('Asia/Jakarta')->format('H:i') }} -
                    {{ \Carbon\Carbon::parse($booking->end_time)->timezone('Asia/Jakarta')->format('H:i') }} WIB
                </p>
                <p style="margin: 8px 0;">
                    <strong style="color: #111827; display: inline-block; width: 130px;">Nominal DP</strong> : Rp
                    {{ number_format($booking->dp_amount, 0, ',', '.') }}
                </p>
            </div>

            <p>Silakan periksa dashboard untuk melihat rincian sisa pelunasan dan mengelola jadwal ini.</p>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/admin/dashboard') }}"
                    style="background-color: #b91c1c; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; font-size: 16px;">
                    Buka Dashboard Admin
                </a>
            </div>

        </div>
    </div>

</body>

</html>
