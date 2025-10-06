<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }} - PAMDES WANASABA</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <x-navbar></x-navbar>
    <section>
        
        <h3>Selamat datang di Dashboard Admin</h3>
        <p>Di sini Anda bisa memantau data PAMDES.</p>
    </section>

    <div class="card-container">
        <!-- Card Jumlah User -->
        <div class="card">
            <h4>Jumlah Pelanggan</h4>
            <p class="count">{{ $jumlahUser }} Orang</p>
        </div>
    </div>
</body>
</html>
    

    
