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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 = inactive, 1=active');
            $table->string('season')->nullable();

            $table->bigInteger('category_id')->nullable()->unsigned();
            $table->bigInteger('city_id')->nullable()->unsigned();
            $table->bigInteger('destination_id')->nullable()->unsigned();
            $table->bigInteger('meta_id')->nullable()->unsigned();

            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('destination_id')->references('id')->on('destinations')->onDelete('cascade');
            $table->foreign('meta_id')->references('id')->on('metas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
