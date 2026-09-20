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
        Schema::table('jurusans', function (Blueprint $table) {
            if (! Schema::hasColumn('jurusans', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('nama_jurusan');
            }
            if (! Schema::hasColumn('jurusans', 'kode')) {
                $table->string('kode', 10)->nullable()->after('slug');
            }
            if (! Schema::hasColumn('jurusans', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('kode');
            }
            if (! Schema::hasColumn('jurusans', 'kepala_jurusan')) {
                $table->string('kepala_jurusan')->nullable()->after('deskripsi');
            }
            if (! Schema::hasColumn('jurusans', 'status_aktif')) {
                $table->boolean('status_aktif')->default(true)->after('kepala_jurusan');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurusans', function (Blueprint $table) {
            $table->dropColumn(['slug', 'kode', 'deskripsi', 'kepala_jurusan', 'status_aktif']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_active']);
        });
    }
};
