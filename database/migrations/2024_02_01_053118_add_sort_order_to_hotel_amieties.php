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
        Schema::table('hotel_amieties', function (Blueprint $table) {
             $table->integer('sort_order')->after('type')->default(0)->unique();
            // $table->text('image')->after('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_amieties', function (Blueprint $table) {
            //
        });
    }
};
