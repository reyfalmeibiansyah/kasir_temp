<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .product-image {
            height: 100%;
            width: 100%;
            object-fit: cover;
            border-radius: 1rem 0 0 1rem;
        }
        .product-title {
            font-size: 1.75rem;
            font-weight: 700;
        }
        .product-price {
            font-size: 1.25rem;
            color: #28a745;
            font-weight: bold;
        }
        .card-custom {
            border-radius: 1rem;
            height: 100%;
        }
        .equal-height {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        /* Responsive Fix for Small Devices */
        @media (max-width: 767.98px) {
            .product-image {
                border-radius: 1rem 1rem 0 0;
                height: auto;
            }
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row g-4 align-items-stretch">
        <div class="col-md-5">
            <div class="card shadow-sm card-custom h-100">
                <div class="equal-height">
                    <img src="{{ asset('/storage/produks/'.$produk->image) }}" class="product-image" alt="Gambar Produk">
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow-sm card-custom h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="product-title mb-3">{{ $produk->title }}</h3>
                        <p class="product-price mb-3">{{ "Rp " . number_format($produk->price, 2, ',', '.') }}</p>
                        <div class="mb-3">
                            {!! $produk->description !!}
                        </div>
                    </div>
                    <p class="text-muted mt-auto mb-0">Stok: <strong>{{ $produk->stock }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
