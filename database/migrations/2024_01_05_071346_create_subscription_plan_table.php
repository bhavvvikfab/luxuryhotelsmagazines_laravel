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
        Schema::create('subscription_plan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('hotels');
            $table->string('plan_name', 255);
            $table->string('payment_type', 255);
            $table->integer('no_of_days')->unsigned()->nullable()->change();
            $table->integer('trail_days')->unsigned()->nullable()->change();
            $table->datetime('plan_start_date');
            $table->datetime('plan_end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plan');
    }
};
