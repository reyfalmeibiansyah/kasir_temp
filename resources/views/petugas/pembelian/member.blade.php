@extends('layout.sidebar')

@section('content')
<div class="container py-4" style="margin-left: 130px;">
    <h3 class="fw-bold mb-4">Penjualan</h3>

    <div class="mx-auto" style="max-width: 900px;">
        <div class="bg-white rounded-4 shadow-sm p-4">
            <div class="row align-items-stretch">
                {{-- Kiri: Ringkasan Produk --}}
                <div class="col-md-6 mb-4 mb-md-0 d-flex flex-column justify-content-between"
                     style="background-color: #f8f9fa; border: 1px solid #ddd; border-radius: 10px;">
                    <div>
                        <h5 class="fw-bold mb-3">Ringkasan Produk</h5>
                        <table class="table table-bordered mb-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>QTY</th>
                                    <th>Harga</th>
                                    <th>Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($selectedProducts as $product)
                                    <tr>
                                        <td>{{ $product['nama_produk'] }}</td>
                                        <td class="text-center">{{ $product['qty'] }}</td>
                                        <td class="text-end">Rp {{ number_format($product['harga_produk'], 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($product['sub_total'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-end">
                            <p class="mb-1"><strong>Total Harga:</strong> Rp {{ number_format($total_payment, 0, ',', '.') }}</p>
                            <p class="mb-0"><strong>Total Bayar:</strong> Rp {{ number_format($total_payment, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Form Member --}}
                <div class="col-md-6">
                    <form action="{{ route('petugas.pembelian.storeStep2') }}" method="POST">
                        @csrf
                        <input type="hidden" name="total_payment" value="{{ $total_payment }}">
                        <input type="hidden" name="customer_phone" value="{{ request('customer_phone') }}">

                        <h5 class="fw-bold mb-3">Data Member</h5>

                        <div class="mb-3">
                            <label class="form-label">Nama Member</label>
                            <input type="text" name="nama_member" class="form-control" placeholder="Masukkan nama member" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Poin</label>
                            <input type="text" name="points" value="{{ $point ?? 0 }}" class="form-control bg-light" readonly>
                        </div>

                        @if ($member)
                            @if ($member->points > 0)
                                <div class="mb-3">
                                    <p class="mb-1"><strong>Poin Saat Ini:</strong> {{ $member->points }}</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="point_used" id="point_used" value="1">
                                        <label class="form-check-label" for="point_used">
                                            Gunakan poin untuk potongan harga
                                        </label>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted small">Belum memiliki poin.</p>
                            @endif
                        @else
                            <p class="text-danger small">Member baru — poin dimulai dari 0.</p>
                        @endif

                        <button type="submit" class="btn btn-primary w-100">Selanjutnya</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
