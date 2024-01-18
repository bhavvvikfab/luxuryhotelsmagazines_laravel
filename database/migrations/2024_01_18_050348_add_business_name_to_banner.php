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
        Schema::table('banner', function (Blueprint $table) {
            $table->string('business_name')->after('id');
            $table->string('business_link')->after('business_name');
            $table->string('email')->after('business_link');
            $table->string('banner_catagory')->after('title');
            $table->string('banner_type')->after('banner_catagory');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banner', function (Blueprint $table) {
            //
        });
    }
};
