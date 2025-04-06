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
            $table->foreignId('supplier_id')->constrained('suppliers', 'supplier_id')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('admins', 'admin_id')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories', 'id')->cascadeOnDelete();
            $table->string('name');
            $table->text('description');
            $table->integer('quantity');
            $table->unsignedInteger('price')->default(0);
            $table->date('expire_date');
            $table->string('status')->default(\App\Enums\SurplusStatus::Approved);
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
