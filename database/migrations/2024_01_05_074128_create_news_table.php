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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('hotels');
            $table->string('bussiness_name', 255);
            $table->string('country', 255);
            $table->string('full_name', 255);
            $table->string('email_address', 255);
            $table->text('news_title');
            $table->text('news_desc');
            $table->text('news_image');
            $table->text('youtube_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
