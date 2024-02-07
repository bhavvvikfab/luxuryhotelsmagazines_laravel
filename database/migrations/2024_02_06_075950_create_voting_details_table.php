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
    Schema::create('voting_details', function (Blueprint $table) {
        $table->id();
        $table->bigInteger('hotel_id')->unsigned()->nullable();
        $table->tinyInteger('news_id')->nullable();
        $table->tinyInteger('type');
        $table->string('name', 255);
        $table->string('email', 255);
        $table->text('description');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voting_details');
    }
};
