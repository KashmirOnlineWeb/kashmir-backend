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
        Schema::create('booking_packages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('booking_id')->nullable()->unsigned();
            $table->bigInteger('package_id')->nullable();

            $table->string('name')->nullable();
            $table->text('package_content')->nullable();
            $table->decimal('price');
            $table->string('slug')->nullable();
            $table->string('season')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->string('accommodations')->nullable();
            $table->string('status')->nullable();

            $table->mediumText('content')->nullable();
            $table->string('available_slots')->nullable();
            $table->string('budget_type')->nullable();
            $table->string('currency')->nullable();
            $table->bigInteger('destination_id')->nullable();
            $table->string('days')->nullable();
            $table->string('nights')->nullable();

            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            $table->mediumText('itenery_content')->nullable();
            $table->string('hotel_star')->nullable();
            $table->string('max_capacity')->nullable();
            $table->boolean('is_special')->default(0);

            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_packages');
    }
};
