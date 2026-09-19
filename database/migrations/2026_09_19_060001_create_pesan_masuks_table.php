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
        Schema::create('pesan_masuks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusans')->onDelete('cascade');
            $table->string('nama_pengirim');
            $table->string('email');
            $table->string('subjek')->nullable();
            $table->text('pesan');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesan_masuks');
    }
};
