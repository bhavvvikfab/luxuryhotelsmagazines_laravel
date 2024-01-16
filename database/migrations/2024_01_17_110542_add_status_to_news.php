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
        Schema::table('news', function (Blueprint $table) {
            $table->string('status')->after('news_image');
            $table->string('catagory')->after('status');
            $table->text('editor_choice')->after('catagory');
            $table->string('contact_no')->after('editor_choice');
            $table->string('news_views')->after('contact_no');
            $table->string('news_likes')->after('news_views');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            //
        });
    }
};
