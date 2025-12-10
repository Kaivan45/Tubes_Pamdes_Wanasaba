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
         <button class="menu-toggle" onclick="toggleMenu()">☰</button>
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

       
           <table border="1" class="responsive-table">
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
                        <td data-label="No">{{ $dataSemua->firstItem() + $loop->index }}</td>
                        <td data-label="Jumlah Meteran">{{ $item->meteran }}</td>
                        <td data-label="Harga">
                            Rp {{ number_format($item->harga,0,',','.') }}
                        </td>
                        <td data-label="Jatuh Tempo">{{ $item->tanggal }}</td>
                        <td data-label="Tanggal Bayar">
                            {{ $item->updated_at->format('Y-m-d') }}
                        </td>
                        <td data-label="Status">{{ $item->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top:20px">
            {{ $dataSemua->links() }}
        </div>
        @else
             <div class="empty-state">
                <div class="empty-icon">📄</div>
                <h2>Belum Ada Riwayat</h2>
                <p>Transaksi pembayaran kamu akan tampil di sini.</p>
            </div>
        @endif

        

        <br>

        {{-- Bagian Tagihan Terakhir --}}
        @if ($dataTerakhir && $dataTerakhir->status !== 'Lunas')
            <div class="billing-card">
                    <div class="billing-header">
                        <h2>Tagihan Bulan Ini</h2>
                        <span class="billing-status {{ $dataTerakhir->status === 'Lunas' ? 'paid' : 'unpaid' }}">
                            {{ $dataTerakhir->status }}
                        </span>
                    </div>

                    <div class="billing-body">
                        <div class="billing-item">
                            <span>Jumlah Meteran</span>
                            <strong>{{ $dataTerakhir->meteran }}</strong>
                        </div>

                        <div class="billing-item">
                            <span>Total Tagihan</span>
                            <strong class="price">
                                Rp {{ number_format($dataTerakhir->harga, 0, ',', '.') }}
                            </strong>
                        </div>

                        <div class="billing-item">
                            <span>Jatuh Tempo</span>
                            <strong class="due-date">{{ $dataTerakhir->tanggal }}</strong>
                        </div>
                    </div>

                    <div class="billing-actions">
                        <button id="payButton" class="btn-primary">
                            💳 Bayar Sekarang
                        </button>

                        <div id="paymentOptions" class="payment-options">
                            <button id="tunaiButton" class="btn-outline">
                                💵 Tunai
                            </button>
                            <button id="nonTunaiButton" class="btn-outline">
                                📱 Non Tunai
                            </button>
                        </div>

                        <p id="waitingMessage" class="waiting-msg">
                            ⏳ Silakan tunggu konfirmasi admin...
                        </p>
                    </div>
            </div>
        @else
           <div class="empty-state success">
                <div class="empty-icon">✅</div>
                <h2>Tidak Ada Tagihan</h2>
                <p>Semua tagihan kamu sudah <strong>lunas</strong>. Terima kasih 🙏</p>
            </div>
        @endif
    </section>

    <script>
        function toggleMenu() {
            document.getElementById("menu").classList.toggle("show");
        }
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
