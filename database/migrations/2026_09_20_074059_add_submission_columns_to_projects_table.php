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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('file_hasil')->nullable()->after('tenggat_waktu');
            $table->text('catatan_worker')->nullable()->after('file_hasil');
            $table->enum('status_review', ['draft', 'submitted', 'revision', 'approved'])->default('draft')->after('catatan_worker');
            $table->text('catatan_revisi_admin')->nullable()->after('status_review');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['file_hasil', 'catatan_worker', 'status_review', 'catatan_revisi_admin']);
        });
    }
};
