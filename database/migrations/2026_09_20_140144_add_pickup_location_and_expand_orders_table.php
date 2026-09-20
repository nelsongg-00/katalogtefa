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
        // 1. Tambah lokasi_pengambilan ke tabel jurusans
        if (Schema::hasTable('jurusans') && ! Schema::hasColumn('jurusans', 'lokasi_pengambilan')) {
            Schema::table('jurusans', function (Blueprint $table) {
                $table->string('lokasi_pengambilan')->nullable()->after('status_aktif');
            });
        }

        // 2. Perluas tabel orders untuk mendukung checkout produk fisik
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (! Schema::hasColumn('orders', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('order_code')->constrained('users')->nullOnDelete();
                }
                if (! Schema::hasColumn('orders', 'product_id')) {
                    $table->foreignId('product_id')->nullable()->after('service_id')->constrained('products')->nullOnDelete();
                }
                if (! Schema::hasColumn('orders', 'department_id')) {
                    $table->foreignId('department_id')->nullable()->after('product_id')->constrained('jurusans')->nullOnDelete();
                }
                if (! Schema::hasColumn('orders', 'jumlah')) {
                    $table->integer('jumlah')->default(1)->after('department_id');
                }
                if (! Schema::hasColumn('orders', 'total_harga')) {
                    $table->bigInteger('total_harga')->nullable()->after('total_biaya');
                }
                if (! Schema::hasColumn('orders', 'metode_pembayaran')) {
                    $table->string('metode_pembayaran')->default('cod')->after('total_harga');
                }
                if (! Schema::hasColumn('orders', 'metode_pengiriman')) {
                    $table->string('metode_pengiriman')->default('pickup')->after('metode_pembayaran');
                }
                if (! Schema::hasColumn('orders', 'lokasi_pengambilan')) {
                    $table->string('lokasi_pengambilan')->nullable()->after('metode_pengiriman');
                }
                if (! Schema::hasColumn('orders', 'catatan_pelanggan')) {
                    $table->text('catatan_pelanggan')->nullable()->after('catatan');
                }
                $table->string('status', 50)->default('menunggu_konfirmasi')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('jurusans') && Schema::hasColumn('jurusans', 'lokasi_pengambilan')) {
            Schema::table('jurusans', function (Blueprint $table) {
                $table->dropColumn('lokasi_pengambilan');
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $columnsToDrop = [];
                if (Schema::hasColumn('orders', 'user_id')) {
                    $table->dropForeign(['user_id']);
                    $columnsToDrop[] = 'user_id';
                }
                if (Schema::hasColumn('orders', 'product_id')) {
                    $table->dropForeign(['product_id']);
                    $columnsToDrop[] = 'product_id';
                }
                if (Schema::hasColumn('orders', 'department_id')) {
                    $table->dropForeign(['department_id']);
                    $columnsToDrop[] = 'department_id';
                }
                foreach (['jumlah', 'total_harga', 'metode_pembayaran', 'metode_pengiriman', 'lokasi_pengambilan', 'catatan_pelanggan'] as $col) {
                    if (Schema::hasColumn('orders', $col)) {
                        $columnsToDrop[] = $col;
                    }
                }
                if (! empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
            });
        }
    }
};
