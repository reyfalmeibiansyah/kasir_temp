@extends('layout.sidebar')
@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-flex align-items-center">
                        <li class="breadcrumb-item"><a href="index.html" class="link"><i class="mdi mdi-home-outline fs-4"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pembelian</li>
                    </ol>
                </nav>
                <h1 class="mb-0 fw-bold">Data Pembelian</h1> 
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Penjualan</h5>
                <button class="btn btn-light btn-sm">Export Penjualan (.xlsx)</button>
            </div>
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <label for="entries" class="form-label me-2">Tampilkan</label>
                        <select id="entries" class="form-select d-inline-block w-auto">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                            <option>100</option>
                        </select>
                        <span class="ms-2">entri</span>
                    </div>
                    <div>
                        <input type="text" class="form-control w-auto d-inline-block" placeholder="Cari">
                    </div>
                </div>
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Nama Pelanggan</th>
                            <th>Tanggal Penjualan</th>
                            <th>Total Harga</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse($penjualans as $penjualan)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $penjualan->members->nama_member }}</td>
                                <td>{{ \Carbon\Carbon::parse($penjualan->tanggal_penjualan)->format('Y-m-d') }}</td>
                                <td>Rp. {{ number_format($penjualan->total_payment, 0, ',', '.') }}</td>
                                <td>{{ $penjualan->members->nama_member ?? 'Petugas' }}</td>
                                {{-- <td>
                                    <a href="{{ route('penjualan.show', $penjualan->id) }}" class="btn btn-warning btn-sm">Lihat</a>
                                    <a href="{{ route('penjualan.download', $penjualan->id) }}" class="btn btn-primary btn-sm">Unduh Bukti</a>
                                </td> --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data penjualan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
