<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail__penjualans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('penjualan_id');
            $table->bigInteger('produk_id');
            $table->Integer('qty');
            $table->decimal('price');
            $table->decimal('sub_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail__penjualans');
    }
};
