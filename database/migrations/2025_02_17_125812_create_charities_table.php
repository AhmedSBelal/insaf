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
        Schema::create('charities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('charity_id')->unique()->constrained('users', 'id')->onDelete('cascade')->unique();
            $table->foreignId('admin_id')->nullable()->constrained('admins', 'admin_id')->onDelete('cascade');
            $table->string('phone_number');
            $table->string('status')->default(\App\Enums\CharityStatus::Pending->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charities');
    }
};
