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
        $tables = [
            'background_histories',
            'general_information', 
            'how_to_reachs',
            'locations',
            'religious_places',
            'safety_information',
            'shopping_places',
            'sight_seeings',
            'things_to_be_noteds',
            'things_to_dos'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'repeater_content')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->longText('repeater_content')->nullable()->change();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'background_histories',
            'general_information', 
            'how_to_reachs',
            'locations',
            'religious_places',
            'safety_information',
            'shopping_places',
            'sight_seeings',
            'things_to_be_noteds',
            'things_to_dos'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'repeater_content')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->text('repeater_content')->nullable()->change();
                });
            }
        }
    }
};
