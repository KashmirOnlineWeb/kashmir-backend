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
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('address');
            $table->bigInteger('city_id')->nullable()->unsigned();
            $table->bigInteger('meta_id')->nullable()->unsigned();
            $table->string('contact')->comment('contact phone number');
            $table->text('content')->nullable();
            $table->text('description')->nullable();
            $table->text('facilities')->nullable();
            $table->string('google_map');
            $table->text('how_to_reach')->nullable();
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->text('introduction')->nullable();
            $table->text('referral_system')->nullable();
            $table->text('trauma_services')->nullable();
            $table->string('website_url')->nullable();
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
        Schema::dropIfExists('hospitals');
    }
};