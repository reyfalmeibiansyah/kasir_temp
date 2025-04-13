<?php

namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class PenjualanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Penjualan::with(['members', 'detailPenjualan.produk'])->get()->map(function ($item) {
            $produkList = $item->detailPenjualan->map(function ($detail) {
                return $detail->produk->title . ' (x' . $detail->qty . ')';
            })->implode(', ');

            return [
                'Nama Pelanggan'     => optional($item->members)->nama_member ?? 'Bukan Member',
                'No HP Pelanggan'    => $item->customer_phone ?? '-',
                'Poin Pelanggan'     => optional($item->members)->points ?? 0,
                'Produk'             => $produkList,
                'Total Harga'        => $item->detailPenjualan->sum('sub_total'),
                'Total Bayar'        => $item->total_payment,
                'Total Diskon Poin'  => $item->point_used * 1000, // anggap 1 poin = 1000
                'Total Kembalian'    => $item->change,
                'Tanggal Pembelian'  => Carbon::parse($item->created_at)->timezone('Asia/Jakarta')->format('d-m-Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Pelanggan',
            'No HP Pelanggan',
            'Poin Pelanggan',
            'Produk',
            'Total Harga',
            'Total Bayar',
            'Total Diskon Poin',
            'Total Kembalian',
            'Tanggal Pembelian',
        ];
    }
}
