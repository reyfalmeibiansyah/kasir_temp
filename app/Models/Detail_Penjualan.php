<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'penjualan_id',
        'produk_id',
        'qty',
        'price',
        'sub_total'
    ];

    // Relasi ke tabel penjualans
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    // Relasi ke tabel produks
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
