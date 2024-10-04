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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('package_content')->nullable();
            $table->decimal('price');
            $table->string('slug')->nullable();
            $table->string('season')->nullable();
            $table->bigInteger('category_id')->nullable()->unsigned();
            $table->bigInteger('city_id')->nullable()->unsigned();
            $table->bigInteger('meta_id')->nullable()->unsigned();

            $table->string('accommodations')->nullable();
            $table->string('status')->nullable();

            $table->mediumText('content')->nullable();
            $table->mediumText('addons_editor')->nullable();
            $table->string('available_slots')->nullable();
            $table->string('budget_type')->nullable();
            $table->string('currency')->nullable();
            $table->string('destination')->nullable();
            $table->string('duration')->nullable();
            
            $table->mediumText('exclusions_editor')->nullable();
            $table->mediumText('faqs_content')->nullable();
            $table->mediumText('highlight_editor')->nullable();
            $table->mediumText('illusions_content')->nullable();
            $table->mediumText('itenery_content')->nullable();

            $table->string('image')->nullable();
            $table->string('image_alt')->nullable();
            $table->text('image_gallery')->nullable();
            $table->string('hotel_star')->nullable();
            $table->string('max_capacity')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('meta_id')->references('id')->on('metas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
