@extends('layout.sidebar')

@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-flex align-items-center">
                        <li class="breadcrumb-item">
                            <a href="{{ route('petugas.dashboard') }}" class="link">
                                <i class="mdi mdi-home-outline fs-4"></i>
                            </a>
                        </li>
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
                <h5 class="mb-0">Data Pembelian</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('petugas.pembelian.create') }}" class="btn btn-light btn-sm">
                        <i class="mdi mdi-plus"></i> Tambah Pembelian
                    </a>
                    <a href="{{ route('petugas.pembelian.export') }}" class="btn btn-light btn-sm">
                        Export Penjualan (.xlsx)
                    </a>
                </div>
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
                        <form method="GET" action="{{ route('petugas.pembelian.index') }}" class="d-flex">
                            <input type="text" name="search" class="form-control w-auto d-inline-block me-2" placeholder="Cari nama member..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                            @if(request('search'))
                                <a href="{{ route('petugas.pembelian.index') }}" class="btn btn-secondary btn-sm ms-2">Reset</a>
                            @endif
                        </form>
                    </div>
                </div>

                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Nama Pelanggan</th>
                            <th>Tanggal Pembelian</th>
                            <th>Total Harga</th>
                            <th>Dibuat Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = ($penjualans->currentPage() - 1) * $penjualans->perPage() + 1; @endphp
                        @forelse($penjualans as $penjualan)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ optional($penjualan->members)->nama_member ?? 'Bukan Member' }}</td>
                                <td>{{ \Carbon\Carbon::parse($penjualan->created_at)->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}</td>
                                <td>Rp. {{ number_format($penjualan->total_payment, 0, ',', '.') }}</td>
                                <td>{{ $penjualan->user->name ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('petugas.pembelian.show', $penjualan->id) }}" class="btn btn-info btn-sm mb-1">
                                        <i class="mdi mdi-eye"></i> Lihat
                                    </a>                                                                  
                                    <a href="{{ route('petugas.pembelian.downloadPdf', $penjualan->id) }}" class="btn btn-success btn-sm">
                                        <i class="mdi mdi-download"></i> Unduh PDF
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data pembelian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <<div class="mt-4 d-flex justify-content-between align-items-center flex-wrap">
                    <div class="text-muted small">
                        Menampilkan {{ $penjualans->firstItem() }} sampai {{ $penjualans->lastItem() }} dari total {{ $penjualans->total() }} entri
                    </div>
                    <div class="mt-2 mt-md-0">
                        {{ $penjualans->withQueryString()->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
