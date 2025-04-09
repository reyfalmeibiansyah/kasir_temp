<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    Use HasFactory;

    protected $fillable = [
        'nama_member',
        'nomor_telepon',
        'points'
    ];

    public function penjualans() {
        return $this->hasMany(Penjualan::class, 'member_id');
    }
}
