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
    Schema::create('produks', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('jurusan_id')->nullable();
        $table->enum('tipe', ['Produk Fisik', 'Layanan Jasa'])->default('Produk Fisik');
        $table->string('nama_produk');
        $table->integer('harga');
        $table->text('deskripsi')->nullable();
        $table->string('foto_produk')->nullable();
        $table->string('nomor_wa')->nullable();
        $table->integer('stok')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
