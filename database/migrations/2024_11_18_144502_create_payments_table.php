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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->nullable();
            $table->string('order_id')->nullable();
            $table->string('razorpay_signature_id')->nullable();
            $table->string('status')->nullable();
            $table->string('captured')->nullable();

            $table->string('method')->nullable();
            $table->string('card_id')->nullable();
            $table->text('card')->nullable();
            $table->string('last4')->nullable();
            $table->string('card_network')->nullable();
            $table->string('bank')->nullable();
            $table->string('wallet')->nullable();
            $table->string('vpa')->nullable();

            $table->decimal('amount')->nullable();
            $table->decimal('fee')->nullable();
            $table->decimal('tax')->nullable();

            $table->string('email')->nullable();
            $table->string('contact')->nullable();
            $table->text('notes')->nullable();
            $table->text('description')->nullable();
            $table->string('currency')->nullable();
            $table->text('acquirer_data')->nullable();
            $table->text('upi')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
