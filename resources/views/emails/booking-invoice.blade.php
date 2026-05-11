<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice Booking</title>
</head>

<body>
    <h2>Invoice Booking Alineas Studio</h2>

    <p>Halo, {{ $booking->customer_name }}</p>

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
            <td>: {{ $booking->booking_date }}</td>
        </tr>

        <tr>
            <td>Jam</td>
            <td>: {{ $booking->start_time }}</td>
        </tr>

        <tr>
            <td>Total</td>
            <td>: Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
        </tr>

        <tr>
            <td>Status</td>
            <td>: {{ $booking->payment_status }}</td>
        </tr>
    </table>

    <hr>

    <p>
        Jika ada pertanyaan silakan hubungi admin Alineas Studio.
    </p>

</body>

</html>
