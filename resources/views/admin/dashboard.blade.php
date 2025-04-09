@extends('layout.sidebar')
@section('content')
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-flex align-items-center">
                      <li class="breadcrumb-item"><a href="index.html" class="link"><i class="mdi mdi-home-outline fs-4"></i></a></li>
                      <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                  </nav>
                <h1 class="mb-0 fw-bold">Dashboard</h1> 
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Selamat Datang, petugas!</h4>
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Persentase Penjualan Produk</h5>
                        <canvas id="productChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: ['08 February 2025', '09 February 2025', '10 February 2025', '11 February 2025', '12 February 2025', '13 February 2025', '14 February 2025'],
                datasets: [{
                    label: 'Jumlah Penjualan',
                    data: [2, 12, 72, 35, 10, 30, 55],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    
        const productCtx = document.getElementById('productChart').getContext('2d');
        new Chart(productCtx, {
            type: 'pie',
            data: {
                labels: ['TV', 'HP', 'TWS', 'Botol Minum', 'Mesin Rumput', 'Gizi Seimbang', 'LMS - Jagoscript', 'Buku', 'Niki', 'Lockheed Skunk F-22 Raptor'],
                datasets: [{
                    data: [10, 15, 8, 12, 10, 7, 5, 8, 6, 19],
                    backgroundColor: ['#ff9999','#ffcc66','#9966ff','#ff6699','#6699ff','#66cccc','#ff9966','#66cc66','#ffcc99','#99cccc']
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
@endsection