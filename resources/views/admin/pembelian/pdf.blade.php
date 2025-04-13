<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-end { text-align: right; }
        .mt-3 { margin-top: 1rem; }
        .mt-5 { margin-top: 2rem; }
    </style>
</head>
<body>
    <h3>Indo April</h3>
    <p>Member Status: {{ $pembelian->members ? 'Member' : 'Bukan Member' }}</p>
    <p>No. HP: {{ $pembelian->members->no_hp ?? '-' }}</p>
    <p>Bergabung Sejak: {{ $pembelian->members->created_at ?? '-' }}</p>
    <p>Poin Member: {{ $pembelian->members->points ?? '-' }}</p>

    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>QTY</th>
                <th>Harga</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembelian->detailPenjualan as $detail)
                <tr>
                    <td>{{ $detail->produk->nama_produk }}</td>
                    <td>{{ $detail->qty }}</td>
                    <td>Rp. {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="mt-3">
        <tr>
            <td>Poin Digunakan</td>
            <td>:</td>
            <td>{{ $pembelian->point_used ?? 0 }}</td>
            <td class="text-end"><strong>Total Harga</strong></td>
            <td class="text-end">Rp. {{ number_format($pembelian->total_payment, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td class="text-end"><strong>Harga Setelah Poin</strong></td>
            <td class="text-end">Rp. {{ number_format($pembelian->total_payment, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td class="text-end"><strong>Total Kembalian</strong></td>
            <td class="text-end">Rp. {{ number_format($pembelian->change ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    <p class="mt-5">{{ $pembelian->created_at }} | {{ $pembelian->user->nama ?? 'Petugas' }}</p>
    <p><strong>Terima kasih atas pembelian Anda!</strong></p>
</body>
</html>
