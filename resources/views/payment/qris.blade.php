<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Pembayaran QRIS</title>
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
    </script>
</head>
<body style="text-align:center; margin-top:100px;">
    <h2>Pembayaran Transaksi #{{ $transaksi->id }}</h2>
    <h3>Rp {{ number_format($transaksi->harga, 0, ',', '.') }}</h3>
    <p>Nama: {{ $transaksi->data->user->name ?? 'Pelanggan' }}</p>
    <p>Email: {{ $transaksi->data->user->email ?? 'Dummy email digunakan' }}</p>

    <button id="pay-button" style="padding:10px 20px; background:#008CBA; color:white;">Bayar Sekarang</button>

    <script>
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    alert('Pembayaran berhasil!');
                    window.location.href = "/pelanggan";
                },
                onPending: function(result){
                    alert('Pembayaran menunggu konfirmasi.');
                    console.log(result);
                },
                onError: function(result){
                    alert("Terjadi kesalahan pembayaran!");
                    console.log(result);
                },
                onClose: function(){
                    alert("Kamu menutup pembayaran sebelum selesai.");
                }
            });
        };
    </script>
</body>
</html>
