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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique()->index();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('worker_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('in_progress');
            $table->bigInteger('total_biaya')->nullable();
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
