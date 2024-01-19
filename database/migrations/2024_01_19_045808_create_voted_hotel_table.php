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
        Schema::create('voted_hotel', function (Blueprint $table) {
            $table->id();
            $table->string('hotel_title');
            $table->string('hotel_website');
            $table->string('hotel_description');
            $table->text('hotel_thumbnail');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voted_hotel');
    }
};
