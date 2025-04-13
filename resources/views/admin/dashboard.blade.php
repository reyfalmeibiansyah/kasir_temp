@extends('layout.sidebar')

@section('content')
<style>
    .with-sidebar {
        padding-left: 250px; /* Sesuaikan dengan lebar sidebar */
    }

    @media (max-width: 768px) {
        .with-sidebar {
            padding-left: 0;
        }
    }
</style>

<div class="with-sidebar py-4">
    <div class="container-fluid">
        <div class="row gy-4">
            <div class="col-12">
                <h4 class="fw-semibold">Selamat Datang, Admin!</h4>
            </div>

            <!-- Grafik Statistik Penjualan -->
            <div class="col-xl-8 col-lg-7 col-md-12">
                <div class="card shadow-sm rounded-4">
                    <div class="card-body">
                        <h5 class="card-title">Statistik Penjualan</h5>
                        <canvas id="salesChart" height="220"></canvas>
                    </div>
                </div>
            </div>

            <!-- Grafik Pie Persentase Produk -->
            <div class="col-xl-4 col-lg-5 col-md-12">
                <div class="card shadow-sm rounded-4">
                    <div class="card-body">
                        <h5 class="card-title">Persentase Penjualan Produk</h5>
                        <canvas id="productChart" height="220"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data untuk Grafik Batang Statistik Penjualan (Jumlah Produk Terjual)
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesData = @json($salesData);

    new Chart(salesCtx, {
        type: 'bar',
        data: {
            labels: salesData.dates,
            datasets: [{
                label: 'Jumlah Produk Terjual',
                data: salesData.sales, // Menampilkan jumlah produk yang terjual
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value; // Menampilkan jumlah produk
                        }
                    }
                }
            }
        }
    });

    // Data untuk Grafik Pie Persentase Produk
    const productCtx = document.getElementById('productChart').getContext('2d');
    const produkLabels = @json(array_keys($produkPercentages));  // Gunakan PHP array_keys
    const produkData = @json(array_values($produkPercentages));  // Gunakan PHP array_values

    new Chart(productCtx, {
        type: 'pie',
        data: {
            labels: produkLabels,
            datasets: [{
                data: produkData,
                backgroundColor: [
                    '#ff9999','#ffcc66','#9966ff','#ff6699','#6699ff',
                    '#66cccc','#ff9966','#66cc66','#ffcc99','#99cccc'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection
