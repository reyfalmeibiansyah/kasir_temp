@extends('layout.sidebar')

@section('content')
<div class="px-4 py-4" style="max-width: 900px; margin-left: 280px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-3">
            <li class="breadcrumb-item">Pembayaran</li>
        </ol>
    </nav>

    <h2 class="fw-bold mb-4">Pembayaran</h2>

    <div class="bg-white shadow-sm rounded-4 p-4">
        <div class="d-flex justify-content-between mb-4">
            <div>
                <a href="{{ route('petugas.pembelian.downloadPdf', $penjualan->id) }}" class="btn btn-primary">Unduh</a>
                <a href="{{ route('petugas.pembelian.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
            <div class="text-end">
                <small>Invoice – #{{ $penjualan->invoice_number }}</small><br>
                <small>{{ \Carbon\Carbon::parse($penjualan->created_at)->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}</small>
            </div>
        </div>

        <table class="table table-borderless">
            <thead class="border-bottom">
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Quantity</th>
                    <th class="text-end">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalAsli = 0;
                @endphp
                @foreach($penjualan->detailPenjualan as $item)
                @php
                    $totalAsli += $item->sub_total;
                @endphp
                <tr class="border-b border-gray-200">
                    <td class="py-2">{{ optional($item->produk)->title }}</td>
                    <td class="py-2">Rp. {{ number_format(optional($item->produk)->price, 0, ',', '.') }}</td>
                    <td class="py-2">{{ optional($item)->qty }}</td>
                    <td class="py-2">Rp. {{ number_format($item->sub_total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row bg-light rounded-3 p-3 mt-4">
            <div class="col-md-4">
                <div class="fw-semibold small text-muted">POIN DIGUNAKAN</div>
                <div class="fw-bold">{{ $penjualan->point_used }}</div>
            </div>
            <div class="col-md-4">
                <div class="fw-semibold small text-muted">KASIR</div>
                <div class="fw-bold">{{ $penjualan->user->name ?? 'Petugas' }}</div>
            </div>
            <div class="col-md-4 text-end">
                <div class="fw-semibold small text-muted">KEMBALIAN</div>
                <div class="fw-bold">Rp. {{ number_format($penjualan->change, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <div class="bg-dark text-white rounded-4 p-4 text-end" style="min-width: 240px;">
                <div class="fw-semibold small">TOTAL PEMBAYARAN</div>
                <h4 class="fw-bold text-white">Rp. {{ number_format($penjualan->total_payment, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
</div>
@endsection
