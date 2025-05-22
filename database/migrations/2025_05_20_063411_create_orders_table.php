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
            $table->string('nama_pemesan');
            $table->string('no_hp');
            $table->string('nama_barang');
            $table->enum('jenis', ['camilan-kiloan', 'camilan-satuan', 'non-camilan']);
            $table->enum('status', ['pending', 'lunas', 'pengiriman', 'diterima'])->default('pending');
            $table->integer('harga'); // harga per barang atau per kg
            $table->float('berat')->nullable(); // wajib untuk camilan
            $table->integer('jumlah')->nullable(); // jumlah item (wajib untuk camilan-satuan & non-camilan)
            $table->integer('biaya')->default(0);
            $table->text('catatan')->nullable();
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
