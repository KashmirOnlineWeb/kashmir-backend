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
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('restaurant_content');
            $table->string('slug')->nullable()->after('status');
            $table->string('google_map')->nullable()->after('status');
            $table->string('contact')->nullable()->after('status');
            $table->string('address')->nullable()->after('status');
            $table->string('restaurant_type')->nullable()->after('status');
            $table->text('description')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->text('restaurant_content')->nullable();
            $table->dropColumn('slug');
            $table->dropColumn('google_map');
            $table->dropColumn('contact');
            $table->dropColumn('address');
            $table->dropColumn('restaurant_type');
            $table->dropColumn('description');
        });
    }
};
