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
        Schema::table('special_offer', function (Blueprint $table) {
            $table->text('description')->after('to_date');
            // $table->text('redeem_link')->after('description');
            // $table->tinyInteger('type')->after('news_id');
            // $table->tinyInteger('news_id')->after('hotel_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('special_offer', function (Blueprint $table) {
            //
        });
    }
};
