<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>{{ $title }} - PAMDES WANASABA</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<body>
    <x-navbar></x-navbar>
    <section>
        
        <h3>Selamat datang di Dashboard Admin</h3>
        <p>Di sini Anda bisa memantau data PAMDES.</p>
    </section>

    <div class="dashboard-row">
    
    <!-- Card Jumlah User -->
    <div class="card user-card">
        <h4>Jumlah Pelanggan</h4>
        <p class="count">{{ $jumlahUser }} Orang</p>
    </div>

    <!-- Chart -->
    <div class="chart-card">
        <h4>Pemasukan Tahun {{ $tahun }}</h4>
        <div class="chart-wrapper">
            <canvas id="pemasukanChart"></canvas>
        </div>
    </div>

</div>
    
    <script src="{{ asset('js/script.js') }}"></script>
    <script>
        const ctx = document.getElementById('pemasukanChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                    'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                ],
                datasets: [{
                    label: 'Pemasukan (Rp)',
                    data: @json($pemasukanBulanan),
                    backgroundColor: '#0077b6',
                    borderRadius: 6,      
                    barThickness: 'flex'  
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, 

                plugins: {
                    legend: {
                        display: true
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>

</body>
</html>
    

    
