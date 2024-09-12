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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('amenities')->nullable();
            $table->boolean('balcony')->default(0)->comment('0=false,1=true');
            $table->boolean('breakfast')->default(0)->comment('0=false,1=true');
            $table->string('contact')->nullable()->comment('contact phone number');
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('location')->nullable();
            $table->decimal('price');
            $table->string('star')->nullable();
            $table->decimal('tax');
            $table->integer('total_lobbys');
            $table->integer('total_rooms');
            $table->integer('total_washrooms');
            $table->text('highlights_content')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 = inactive, 1=active');
            $table->bigInteger('meta_id')->nullable()->unsigned();
            $table->bigInteger('city_id')->nullable()->unsigned();
            $table->timestamps();

            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('meta_id')->references('id')->on('metas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
