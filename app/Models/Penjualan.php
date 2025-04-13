<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'member_id',
        'produk_id',
        'customer_phone',
        'is_member',
        'total_payment',
        'point_used',
        'change',
        // 'produk_id',    // pastikan field ini juga diisi
        // 'qty',          // kalau ada di form
    ];

    // Relasi ke Member
    public function members()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    // Relasi ke User (petugas)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke detail penjualan
    public function detailPenjualan()
    {
        return $this->hasMany(Detail_Penjualan::class, 'penjualan_id');
    }

    // Relasi ke Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
