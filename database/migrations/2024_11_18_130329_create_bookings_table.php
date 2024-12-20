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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('package_id')->nullable();
            $table->bigInteger('user_id')->nullable()->unsigned();
            $table->string('razorpay_order_id')->nullable();
            $table->string('status')->nullable();
            $table->string('invoice_id')->nullable();
            $table->string('adults')->nullable();
            $table->string('children')->nullable();
            $table->decimal('amount')->nullable();
            $table->string('gst')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
