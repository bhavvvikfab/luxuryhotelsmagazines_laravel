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
        Schema::create('package_price', function (Blueprint $table) {
            $table->id();
            $table->string('package_catagory');
            $table->string('package_name');
            $table->decimal('package_original_price');
            $table->decimal('package_price')->nullable();
            $table->decimal('hotel_package_price')->nullable();
            $table->decimal('package_validity');
            $table->string('package_validity_title');
            $table->string('package_inner_title')->nullable();
            $table->string('package_inner_sub_title')->nullable();
            $table->text('package_inner_content')->nullable();
            $table->date('package_expiry_date');
            $table->string('package_action');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_price');
    }
};
