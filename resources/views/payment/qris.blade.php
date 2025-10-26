<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran QRIS</title>
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
    </script>
</head>
<body style="font-family: sans-serif; text-align:center; margin-top:100px;">
    <h2>Pembayaran Transaksi #{{ $transaksi->id }}</h2>
    <p>Total Tagihan:</p>
    <h3>Rp {{ number_format($transaksi->harga, 0, ',', '.') }}</h3>
    <button id="pay-button" style="padding:10px 20px; background:#008CBA; color:white; border:none; border-radius:5px;">Bayar Sekarang</button>

    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){ window.location.href = "/"; },
                onPending: function(result){ console.log(result); },
                onError: function(result){ alert("Terjadi kesalahan!"); },
                onClose: function(){ alert("Kamu menutup pembayaran sebelum selesai."); }
            });
        };
    </script>
</body>
</html>
