<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'image',
        'title',
        'description',
        'price',
        'stock',
    ];

    // public function items() {
    //     return $this->hasMany(Detail_Penjualan::class, 'id');
    // }

    // public function detailPenjualans() {
    //     return $this->hasMany(Detail_Penjualan::class, 'id');
    // }
}