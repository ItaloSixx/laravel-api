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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->unsignedBigInteger('roomreservation_id')->unique();
            $table->string('customer_first_name');
            $table->string('customer_last_name');
            $table->date('arrival_date');
            $table->date('departure_date');
            $table->integer('guest_count');
            $table->string('guest_type');
            $table->string('meal_plan')->nullable();
            $table->string('currency_code', 3);
            $table->decimal('total_price', 10, 2);
            $table->date('date');
            $table->time('time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
