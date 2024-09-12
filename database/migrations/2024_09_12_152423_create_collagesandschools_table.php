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
        Schema::create('collagesandschools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->tinyInteger('status')->default(0)->comment('0 = inactive, 1=active');
            $table->string('address')->nullable();
            $table->bigInteger('city_id')->nullable()->unsigned();
            $table->bigInteger('meta_id')->nullable()->unsigned();
            $table->string('board')->nullable();
            $table->text('content')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
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
        Schema::dropIfExists('collagesandschools');
    }
};
