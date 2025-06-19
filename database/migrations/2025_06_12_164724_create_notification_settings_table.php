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
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('general_notification')->default(true);
            $table->boolean('order_updates')->default(true);
            $table->boolean('promotions_offers')->default(true);
            $table->boolean('announcements')->default(true);
            $table->boolean('call_sound')->nullable();
            $table->boolean('vibration')->default(true);
            $table->json('notification_types')->nullable(); // ['push', 'email', 'sms']
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
