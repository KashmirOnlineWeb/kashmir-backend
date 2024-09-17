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
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->dropColumn('pharmacies_content');
            $table->string('contact')->nullable()->comment('contact phone number')->after('status');
            $table->string('working_hours')->nullable()->after('status');
            $table->string('location')->nullable()->after('status');
            $table->string('google_map')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->text('pharmacies_content')->nullable();
            $table->dropColumn('contact');
            $table->dropColumn('working_hours');
            $table->dropColumn('location');
            $table->dropColumn('google_map');
        });
    }
};
