<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
               <li>
                     <form action="{{ route('logout') }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit">
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>
    <section>
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
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataSemua as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->meteran }}</td>
                            <td>Rp. {{ $item->harga }}</td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->updated_at->format('d-m-Y') }}</td>
                            <td>{{ $item->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
        </table>

        <br>
        
        <div>
            @if ($dataTerakhir && $dataTerakhir->status !== 'Lunas')
                <div class="container">
                    <h1>Tagihan</h1>
                </div>
                <div class="tagihan">
                    Jumlah Meteran: {{ $dataTerakhir->meteran }} <br>
                    Harga: {{ $dataTerakhir->harga }} <br>
                    Jatuh Tempo: {{ $dataTerakhir->tanggal }} <br>
                    Status: {{ $dataTerakhir->status }} <br>
                    <a href="{{ route('payment.pay', $dataTerakhir->id) }}">Lakukan Pembayaran</a>
                </div>
            @else
                <div class="container">
                    <h1>Tidak ada Tagihan</h1>
                </div>
            @endif
             
        </div>
    </section>
    
    
</body>
</html>