@extends('layout.sidebar')

@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-flex align-items-center">
                        <li class="breadcrumb-item"><a href="{{ route('petugas.produks.index') }}" class="link"><i class="mdi mdi-home-outline fs-4"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Produk</li>
                    </ol>
                </nav>
                <h1 class="mb-0 fw-bold">Data Produk</h1> 
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Daftar Produk</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>#</th>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Dibuat Pada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @forelse($produks as $produk)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>
                                    <img src="{{ asset('storage/produks/' . $produk->image) }}" alt="gambar produk" width="60">
                                </td>
                                <td>{{ $produk->title }}</td>
                                <td>{!! $produk->description !!}</td>
                                <td>Rp. {{ number_format($produk->price, 0, ',', '.') }}</td>
                                <td>{{ $produk->stock }}</td>
                                <td>{{ \Carbon\Carbon::parse($produk->created_at)->format('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada produk tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
