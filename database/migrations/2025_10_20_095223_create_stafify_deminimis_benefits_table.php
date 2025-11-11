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
        Schema::create('stafify_deminimis_benefits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('cola', 10, 2)->nullable();
            $table->decimal('rice_subsidy', 10, 2)->nullable();
            $table->decimal('meal_allowance', 10, 2)->nullable();
            $table->decimal('uniform_clothing', 10, 2)->nullable();
            $table->decimal('laundry_allowance', 10, 2)->nullable();
            $table->decimal('medical_allowance', 10, 2)->nullable();
            $table->decimal('collective_bargaining_agreement', 10, 2)->nullable();
            $table->decimal('total_non_taxable_13', 10, 2)->nullable();
            $table->decimal('service_incentive_leave', 10, 2)->nullable();
            $table->decimal('paid_time_off', 10, 2)->nullable();
            $table->decimal('other_allowances', 10, 2)->nullable();
            $table->decimal('total_non_taxable_benefits', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('stafify_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stafify_deminimis_benefits');
    }
};