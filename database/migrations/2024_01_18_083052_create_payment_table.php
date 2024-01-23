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
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('user_id');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->string('payment_type');
            $table->string('charge_subscription_id');
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->integer('total_days')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
