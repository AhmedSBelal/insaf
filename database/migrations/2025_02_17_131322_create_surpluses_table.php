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
        Schema::create('surpluses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->references('supplier_id')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('admins')->references('admin_id')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->integer('quantity');
            $table->unsignedInteger('price')->default(0);
            $table->date('expire_date');
            $table->string('status')->default(\App\Enums\SurplusStatus::Pending);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surpluses');
    }
};
