@extends('layout.sidebar')

@section('content')
<div class="container py-4" style="margin-left: 130px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-3">
        </ol>
    </nav>

    <h3 class="fw-bold mb-4">Penjualan</h3>

    <div class="mx-auto" style="max-width: 900px;">
        <div class="bg-white rounded-4 shadow-sm p-4">
            <div class="row align-items-stretch">
                {{-- Kiri: Info Produk --}}
                <div class="col-md-6 mb-4 mb-md-0 d-flex flex-column justify-content-between"
                     style="background-color: #f8f9fa; border: 1px solid #ddd; border-radius: 10px; min-height: 220px;">
                    <div>
                        <h5 class="fw-bold mb-3">Produk yang dipilih</h5>
                        @if(isset($produk))
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="mb-0">{{ $produk->title }}</p>
                                    <small class="text-muted">Rp. {{ number_format($produk->price) }} X {{ $jumlah }}</small>
                                </div>
                                <p class="fw-semibold">Rp. {{ number_format($produk->price) }}</p>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <h6 class="fw-semibold">Total</h6>
                                <h5 class="fw-bold">Rp. {{ number_format($total) }}</h5>
                            </div>
                        @else
                            <p class="text-danger">Data produk tidak tersedia.</p>
                        @endif
                    </div>
                </div>

                {{-- Kanan: Form Member & Bayar --}}
                <div class="col-md-6">
                    <form id="formPenjualan" method="GET">
                        @csrf

                        {{-- Hidden input data penting --}}
                        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                        <input type="hidden" name="jumlah" value="{{ $jumlah }}">
                        <input type="hidden" name="harga" value="{{ $produk->price }}">
                        <input type="hidden" name="total_payment" value="{{ $total }}">

                        {{-- Hidden input untuk customer_phone (diisi otomatis lewat JS) --}}
                        <input type="hidden" name="customer_phone" id="hiddenCustomerPhone">

                        <div class="mb-3">
                            <label class="form-label">Member Status 
                                <span class="text-danger">Dapat juga membuat member</span>
                            </label>
                            <select class="form-select" name="is_member" id="memberStatus">
                                <option value="bukan_member" {{ old('is_member') === 'bukan_member' ? 'selected' : '' }}>Bukan Member</option>
                                <option value="member" {{ old('is_member') === 'member' ? 'selected' : '' }}>Member</option>
                            </select>
                        </div>

                        {{-- Input No. HP untuk member --}}
                        <div class="mb-3 {{ old('is_member') === 'member' ? '' : 'd-none' }}" id="phoneInput">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="visible_phone" id="visiblePhone" class="form-control" placeholder="Masukkan No. HP" value="{{ old('customer_phone') }}">
                            @error('customer_phone')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Total Bayar</label>
                            <input type="number" name="total_payment" class="form-control" value="{{ old('total_payment', $total) }}" required>
                            @error('total_payment')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>                                             

                        <button type="submit" class="btn btn-primary w-100">Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script untuk toggle input nomor telepon --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusSelect = document.getElementById('memberStatus');
        const phoneInput = document.getElementById('phoneInput');
        const form = document.getElementById('formPenjualan');
        const visiblePhone = document.getElementById('visiblePhone');
        const hiddenPhone = document.getElementById('hiddenCustomerPhone');

        function togglePhoneInput() {
            if (statusSelect.value === 'member') {
                phoneInput.classList.remove('d-none');
            } else {
                phoneInput.classList.add('d-none');
            }
        }

        statusSelect.addEventListener('change', togglePhoneInput);
        togglePhoneInput(); // trigger saat halaman reload

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            // Salin nomor telepon dari visible ke hidden input
            hiddenPhone.value = visiblePhone.value;

            if (statusSelect.value === 'member') {
                form.action = "{{ route('petugas.pembelian.member') }}";
            } else {
                form.action = "{{ route('petugas.pembelian.store') }}";
                form.method = "POST";
            }

            form.submit();
        });
    });
</script>

@endsection
