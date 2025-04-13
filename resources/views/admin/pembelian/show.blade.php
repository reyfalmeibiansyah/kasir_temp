@extends('layout.sidebar')

@section('content')
<div class="px-4 py-4" style="max-width: 900px; margin-left: 280px;"> {{-- Offset dari sidebar dan lebar dibatasi --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-3">
            <li class="breadcrumb-item">Pembelian</li>
            <li class="breadcrumb-item active" aria-current="page">Detail</li>
        </ol>
    </nav>

    <h2 class="fw-bold mb-4">Detail Pembelian</h2>

    <div class="bg-white shadow-sm rounded-4 p-4">
        <div class="d-flex justify-content-between mb-3">
            <div>
                <strong>Invoice:</strong> #{{ $pembelian->invoice_number }} <br>
                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($pembelian->created_at)->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}<br>
                <strong>Status Member:</strong> {{ $pembelian->members ? 'Member' : 'Non-Member' }}
            </div>
            <div class="text-end">
                <strong>Petugas:</strong> {{ $pembelian->user->name ?? '-' }} <br>
                @if($pembelian->members)
                    <strong>Nama Member:</strong> {{ $pembelian->members->nama_member }} <br>
                    <strong>Telepon:</strong> {{ $pembelian->members->nomor_telepon }}
                @endif
            </div>
        </div>

        <h5 class="fw-bold mt-4 mb-3">Produk Dibeli</h5>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Nama Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembelian->detailPenjualan as $detail)
                <tr>
                    <td>{{ $detail->produk->title }}</td>
                    <td>{{ $detail->qty }}</td>
                    <td>Rp{{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row bg-light rounded-3 p-3 mt-4">
            <div class="col-md-4">
                <div class="fw-semibold small text-muted">POIN DIGUNAKAN</div>
                <div class="fw-bold">{{ $pembelian->point_used }}</div>
            </div>
            <div class="col-md-4">
                <div class="fw-semibold small text-muted">KASIR</div>
                <div class="fw-bold">{{ $pembelian->user->name ?? 'Petugas' }}</div>
            </div>
            <div class="col-md-4 text-end">
                <div class="fw-semibold small text-muted">KEMBALIAN</div>
                <div class="fw-bold">Rp{{ number_format($pembelian->change, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <div class="bg-dark text-white rounded-4 p-4 text-end" style="min-width: 200px;">
                <div class="fw-semibold small">TOTAL</div>
                <h4 class="fw-bold  text-white">Rp{{ number_format($pembelian->total_payment, 0, ',', '.') }}</h4>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.pembelian.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
