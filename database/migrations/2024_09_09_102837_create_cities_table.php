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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->text('description');
            $table->text('highlights_content');
            $table->string('zip_code');
            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->bigInteger('meta_id')->nullable()->unsigned();
            $table->string('time_to_visit')->nullable();
            $table->timestamps();

            $table->foreign('meta_id')->references('id')->on('metas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
