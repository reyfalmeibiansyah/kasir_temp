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
                        <div class="card text-center p-4 shadow" style="border-radius: 1rem;">
                            <h4 class="card-title mb-4">Selamat Datang, Petugas!</h4>
                            <div class="bg-light py-3 rounded" style="font-weight: 500;">
                                <div class="mb-2 text-muted">Jumlah Produk Terjual Hari Ini</div>
                                <div style="font-size: 2.5rem; font-weight: bold; color: #000;">
                                    {{ number_format($produkTerjualHariIni, 0, ',', '.') }} <!-- Menampilkan jumlah produk yang terjual hari ini -->
                                </div>
                                <div class="text-muted">Jumlah total produk yang terjual hari ini.</div>
                                <div class="mt-3 text-muted">Terakhir diperbarui: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
