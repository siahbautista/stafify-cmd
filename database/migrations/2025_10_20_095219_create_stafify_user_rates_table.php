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
        Schema::create('stafify_user_rates', function (Blueprint $table) {
            $table->id('rate_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('hourly_rate', 10, 2)->default(0.00);
            $table->decimal('daily_rate', 10, 2)->default(0.00);
            $table->decimal('monthly_rate', 10, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('stafify_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stafify_user_rates');
    }
};