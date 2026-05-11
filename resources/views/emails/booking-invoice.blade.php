<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice Booking</title>
</head>

<body>
    <h2>Invoice Booking Alineas Studio</h2>

    <p>Halo, {{ $booking->user->name }}</p>

    <p>Terima kasih telah melakukan booking.</p>

    <hr>

    <h3>Detail Booking</h3>

    <table cellpadding="6">
        <tr>
            <td>Kode Booking</td>
            <td>: {{ $booking->booking_code }}</td>
        </tr>

        <tr>
            <td>Paket</td>
            <td>: {{ $booking->package->name }}</td>
        </tr>

        <tr>
            <td>Tanggal</td>
            <td>:
                {{ \Carbon\Carbon::parse($booking->start_time)->timezone('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </td>
        </tr>

        <tr>
            <td>Jam</td>
            <td>: {{ \Carbon\Carbon::parse($booking->start_time)->timezone('Asia/Jakarta')->format('H:i') }} -
                {{ \Carbon\Carbon::parse($booking->end_time)->timezone('Asia/Jakarta')->format('H:i') }} WIB</td>
        </tr>

        <tr>
            <td>Total</td>
            <td>: Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
        </tr>

        <tr>
            <td>DP</td>
            <td>: Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</td>
        </tr>

        <tr>
            <td>Sisa Pelunasan</td>
            <td>: Rp {{ number_format($booking->remaining_balance, 0, ',', '.') }}</td>
        </tr>
    </table>

    <hr>

    <p>
        Jika ada pertanyaan silakan hubungi admin Alineas Studio. <br>Whatsapp: <a href="https://wa.me/6285213385280"
            target="_blank">0852-1338-5280</a>
    </p>

</body>

</html>
