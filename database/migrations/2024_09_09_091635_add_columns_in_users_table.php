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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('id')->nullable();
            $table->string('middle_name')->after('first_name')->nullable();
            $table->string('last_name')->after('middle_name')->nullable();
            $table->string('slug')->after('last_name')->nullable();
            $table->string('dob')->after('slug')->nullable();
            $table->bigInteger('role_id')->after('name')->unsigned()->nullable();
            $table->string('profile_image')->after('role_id')->nullable();
            $table->string('mobile')->after('profile_image')->nullable();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('middle_name');
            $table->dropColumn('last_name');
            $table->dropColumn('slug');
            $table->dropColumn('dob');
            $table->dropForeign('role_id');
            $table->dropColumn('profile_image');
            $table->dropColumn('mobile');
        });
    }
};
