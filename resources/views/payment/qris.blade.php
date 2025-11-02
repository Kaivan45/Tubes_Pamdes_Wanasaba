<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS</title>
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
    </script>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f2f8ff, #e1f0ff);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            background: #fff;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        h2 {
            color: #0066cc;
            margin-bottom: 10px;
        }

        h3 {
            font-size: 28px;
            color: #222;
            margin: 10px 0 5px;
        }

        p {
            color: #666;
            margin-bottom: 30px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .footer {
            margin-top: 25px;
            font-size: 13px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Pembayaran Transaksi #{{ $transaksi->id }}</h2>
        <h3>Rp {{ number_format($transaksi->totalbayar, 0, ',', '.') }}</h3>
        <p>Nama: {{ $transaksi->data->user->name ?? 'Pelanggan' }}</p>

        <button id="pay-button">Bayar Sekarang</button>

        <div class="footer">
            Powered by <strong>Midtrans</strong> • Aman & Cepat
        </div>
    </div>

    <script>
        document.getElementById('pay-button').onclick = function(){
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    alert('✅ Pembayaran berhasil!');
                    window.location.href = "/pelanggan";
                },
                onPending: function(result){
                    alert('🕐 Pembayaran sedang diproses, silakan tunggu.');
                    console.log(result);
                },
                onError: function(result){
                    alert("❌ Terjadi kesalahan dalam pembayaran!");
                    console.log(result);
                },
                onClose: function(){
                    alert("⚠️ Kamu menutup jendela pembayaran sebelum selesai.");
                }
            });
        };
    </script>
</body>
</html>
