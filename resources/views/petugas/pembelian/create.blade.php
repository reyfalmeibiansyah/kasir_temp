@extends('layout.sidebar')
@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 text-center">
                    <h4 class="fw-bold mb-0">Form Pembelian</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('petugas.pembelian.detail') }}" method="POST">
                        @csrf

                        {{-- Pilih Produk --}}
                        <div class="mb-3">
                            <label for="produk_id" class="form-label">Pilih Produk</label>
                            <select name="produk_id" id="produk_id" class="form-select" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($produks as $produk)
                                    <option 
                                        value="{{ $produk->id }}"
                                        data-title="{{ $produk->title }}"
                                        data-stock="{{ $produk->stock }}"
                                        data-price="{{ $produk->price }}"
                                        data-image="{{ asset('storage/produks/' . $produk->image) }}"
                                    >
                                        {{ $produk->title }} (Stok: {{ $produk->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Preview Produk --}}
                        <div id="produk-preview" class="text-center mb-4 d-none">
                            <img id="preview-image" src="" alt="Gambar Produk" class="img-fluid mb-2 rounded" style="max-height: 150px;">
                            <h5 id="preview-title" class="mb-1 fw-bold"></h5>
                            <p class="mb-0">Stok: <span id="preview-stock"></span></p>
                            <p class="mb-2">Harga: Rp. <span id="preview-price"></span></p>
                        </div>

                        {{-- Jumlah dan Tombol --}}
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary" id="btn-kurang">−</button>
                                <input type="number" name="jumlah" id="jumlah" class="form-control text-center" value="1" min="1" required>
                                <button type="button" class="btn btn-outline-secondary" id="btn-tambah">+</button>
                            </div>
                        </div>

                        {{-- Subtotal --}}
                        <div class="mb-4 text-end">
                            <strong>Sub Total: Rp. <span id="subtotal">0</span></strong>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill">Beli Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript --}}
<script>
    const produkSelect = document.getElementById('produk_id');
    const preview = document.getElementById('produk-preview');
    const previewImage = document.getElementById('preview-image');
    const previewTitle = document.getElementById('preview-title');
    const previewStock = document.getElementById('preview-stock');
    const previewPrice = document.getElementById('preview-price');
    const jumlahInput = document.getElementById('jumlah');
    const subtotalDisplay = document.getElementById('subtotal');

    let currentStock = 0;
    let currentPrice = 0;

    produkSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        if (!selected.value) {
            preview.classList.add('d-none');
            return;
        }

        currentStock = parseInt(selected.dataset.stock);
        currentPrice = parseInt(selected.dataset.price);

        previewImage.src = selected.dataset.image;
        previewTitle.textContent = selected.dataset.title;
        previewStock.textContent = currentStock;
        previewPrice.textContent = currentPrice.toLocaleString();

        jumlahInput.value = 1;
        preview.classList.remove('d-none');
        updateSubtotal();
    });

    document.getElementById('btn-tambah').addEventListener('click', () => {
        let jumlah = parseInt(jumlahInput.value || 1);
        if (jumlah < currentStock) {
            jumlahInput.value = jumlah + 1;
            updateSubtotal();
        }
    });

    document.getElementById('btn-kurang').addEventListener('click', () => {
        let jumlah = parseInt(jumlahInput.value || 1);
        if (jumlah > 1) {
            jumlahInput.value = jumlah - 1;
            updateSubtotal();
        }
    });

    jumlahInput.addEventListener('input', () => {
        let jumlah = parseInt(jumlahInput.value);
        if (jumlah > currentStock) {
            jumlahInput.value = currentStock;
        } else if (jumlah < 1 || isNaN(jumlah)) {
            jumlahInput.value = 1;
        }
        updateSubtotal();
    });

    function updateSubtotal() {
        const jumlah = parseInt(jumlahInput.value) || 0;
        subtotalDisplay.textContent = (jumlah * currentPrice).toLocaleString();
    }
</script>

@endsection
