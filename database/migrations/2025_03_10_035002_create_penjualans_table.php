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
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->bigInteger('user_id');
            $table->bigInteger('member_id');
            $table->string('customer_phone');
            $table->enum('is_member', ['bukan_member', 'member']);
            $table->decimal('total_payment');
            $table->integer('point_used');
            $table->decimal('change');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
