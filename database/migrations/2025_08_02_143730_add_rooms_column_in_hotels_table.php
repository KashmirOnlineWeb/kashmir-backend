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
        Schema::table('hotels', function (Blueprint $table) {
            $table->renameColumn('price','min_price');
            $table->decimal('max_price')->after('min_price');
            $table->json('rooms')->nullable()->after('max_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->renameColumn('min_price','price');
            $table->dropColumn('max_price');
            $table->dropColumn('rooms');
        });
    }
};
