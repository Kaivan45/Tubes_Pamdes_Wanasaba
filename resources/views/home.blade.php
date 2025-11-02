<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Dashboard Pelanggan</title>
    <link rel="stylesheet" href="{{ asset('css/style1.css') }}">
</head>
<body>
    <header>
        <div class="logo">
            <h3><a class="logoL" href="/">{{ Auth::user()->name }}</a></h3>
        </div>
        <nav>
            <ul id="menu">
                <li><a href="https://wa.me/6289512996464">Contact Us</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>

    <section>
         @if ($dataSemua && $dataSemua->isNotEmpty())
        <div class="container">
            <h1>History</h1>
        </div>

       
           <table border="1" class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jumlah Meteran</th>
                    <th>Harga</th>
                    <th>Jatuh Tempo</th>
                    <th>Tanggal Bayar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataSemua as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->meteran }}</td>
                        <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>{{ $item->tanggal }}</td>
                        <td>{{ $item->updated_at->format('Y-m-d') }}</td>
                        <td>{{ $item->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>    
        @endif

        

        <br>

        {{-- Bagian Tagihan Terakhir --}}
        @if ($dataTerakhir && $dataTerakhir->status !== 'Lunas')
            <div class="container">
                <h1>Tagihan</h1>
            </div>

            <div class="tagihan">
                Jumlah Meteran: {{ $dataTerakhir->meteran }} <br>
                Harga: Rp. {{ number_format($dataTerakhir->harga, 0, ',', '.') }} <br>
                Jatuh Tempo: {{ $dataTerakhir->tanggal }} <br>
                Status: {{ $dataTerakhir->status }} <br><br>

                <button id="payButton">Lakukan Pembayaran</button>

                <div id="paymentOptions" style="display:none; margin-top:10px;">
                    <button id="tunaiButton">Tunai</button>
                    <button id="nonTunaiButton">Non Tunai</button>
                </div>

                <p id="waitingMessage" style="display:none; color:green; margin-top:10px;">
                    Silakan tunggu konfirmasi admin...
                </p>    
            </div>
        @else
            <p>Tidak ada tagihan yang harus dibayar.</p>
        @endif
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const payButton = document.getElementById("payButton");
            const paymentOptions = document.getElementById("paymentOptions");
            const tunaiButton = document.getElementById("tunaiButton");
            const nonTunaiButton = document.getElementById("nonTunaiButton");
            const waitingMessage = document.getElementById("waitingMessage");

            @if ($dataTerakhir)
                const tagihanId = {{ $dataTerakhir->id }};
            @endif

            payButton?.addEventListener("click", () => {
                paymentOptions.style.display = "block";
            });

            function kirimMetode(method) {
                fetch("{{ route('payment.method') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ id: tagihanId, method: method })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        paymentOptions.style.display = "none";
                        payButton.style.display = "none";

                        if (method === "Tunai") {
                            waitingMessage.style.display = "block";
                        } else if (data.redirect) {
                            // Gunakan link redirect dari controller
                            window.location.href = data.redirect;
                        }
                    } else {
                        alert(data.message || "Terjadi kesalahan saat memproses pembayaran");
                    }
                })
                .catch(err => console.error(err));
            }

            tunaiButton?.addEventListener("click", () => kirimMetode("Tunai"));
            nonTunaiButton?.addEventListener("click", () => kirimMetode("Non Tunai"));
        });
    </script>

</body>
</html>
