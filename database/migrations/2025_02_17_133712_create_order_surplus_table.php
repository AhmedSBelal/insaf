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
        Schema::create('order_surplus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('surplus_id')->nullable()->constrained('surpluses')->nullOnDelete();
            $table->string('surplus_name');
            $table->float('price');
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->unique(['order_id', 'surplus_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_surplus');
    }
};
