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
        Schema::create('supplier_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->foreignId('charity_id')->nullable()->constrained('charities')->onDelete('SET NULL');
            $table->unsignedTinyInteger('rating')->comment('1 to 5');
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->unique(['supplier_id', 'charity_id']); // Prevent multiple ratings per charity per supplier
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_ratings');
    }
};
