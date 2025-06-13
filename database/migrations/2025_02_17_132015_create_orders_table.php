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
            $table->foreignId('charity_id')
                ->nullable()
                ->constrained('charities')
                ->references('charity_id')
                ->onDelete('set null');
            $table->foreignId('supplier_id')
                ->nullable()
                ->constrained('suppliers')
                ->onDelete('set null');
            $table->foreignId('payment_id')
                ->constrained('payments')
                ->references('id')
                ->cascadeOnDelete();
            $table->string('payment_status')
                ->default(\App\Enums\PaymentStatus::Pending);
            $table->unsignedBigInteger('total_price')->default(0);
            $table->string('status')->default(\App\Enums\OrderStatus::Pending);
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
