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
        // Schema::table('subscription_plan', function (Blueprint $table) {
        //     $table->integer('no_of_days')->unsigned()->nullable()->change();
        //     $table->integer('trail_days')->unsigned()->nullable()->change();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscription_plan', function (Blueprint $table) {
            $table->integer('no_of_days')->unsigned()->change();
            $table->integer('trail_days')->unsigned()->change();
        });
    }
};
