<?php

namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PenjualanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Penjualan::with('members')->get()->map(function ($item) {
            return [
                'Nama Pelanggan' => $item->members->nama_member,
                'Tanggal Pembelian' => $item->tanggal_penjualan,
                'Total Harga' => $item->total_payment,
                'Dibuat Oleh' => $item->created_by,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Pelanggan',
            'Tanggal Pembelian',
            'Total Harga',
            'Dibuat Oleh',
        ];
    }
}

