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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_type')->nullable()->after('status')->comment('Booking type like package or hotel');
            $table->bigInteger('hotel_id')->nullable()->after('booking_type');
            $table->json('rooms')->nullable()->after('hotel_id')->comment('In rooms store json data with room detail.');
            $table->dateTime('start_date')->nullable()->after('rooms')->comment('Hotel booking start date');
            $table->dateTime('end_date')->nullable()->after('start_date')->comment('Hotel booking end date');
            $table->string('no_of_rooms')->nullable()->after('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['booking_type','hotel_id','room_type','start_date','end_date','no_of_rooms']);
        });
    }
};
