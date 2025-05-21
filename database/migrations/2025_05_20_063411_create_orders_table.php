<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->enum('jenis', ['camilan', 'non-camilan']);
            $table->float('berat')->nullable(); // hanya untuk camilan
            $table->integer('harga')->nullable(); // untuk non-camilan
            $table->integer('jumlah')->nullable(); // untuk non-camilan
            $table->text('catatan')->nullable();
            $table->integer('biaya'); // hasil perhitungan
            $table->enum('status', ['pending', 'lunas', 'pengiriman', 'diterima'])->default('pending');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
