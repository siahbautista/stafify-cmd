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
        Schema::create('stafify_fringe_benefits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('hazard_pay', 15, 2)->default(0.00);
            $table->decimal('fixed_representation_allowance', 15, 2)->default(0.00);
            $table->decimal('fixed_transportation_allowance', 15, 2)->default(0.00);
            $table->decimal('fixed_housing_allowance', 15, 2)->default(0.00);
            $table->decimal('vehicle_allowance', 15, 2)->default(0.00);
            $table->decimal('educational_assistance', 15, 2)->default(0.00);
            $table->decimal('medical_assistance', 15, 2)->default(0.00);
            $table->decimal('insurance', 15, 2)->default(0.00);
            $table->decimal('membership', 15, 2)->default(0.00);
            $table->decimal('household_personnel', 15, 2)->default(0.00);
            $table->decimal('vacation_expense', 15, 2)->default(0.00);
            $table->decimal('travel_expense', 15, 2)->default(0.00);
            $table->decimal('commissions', 15, 2)->default(0.00);
            $table->decimal('profit_sharing', 15, 2)->default(0.00);
            $table->decimal('fees', 15, 2)->default(0.00);
            $table->decimal('total_taxable_13', 15, 2)->default(0.00);
            $table->decimal('other_taxable', 15, 2)->default(0.00);
            $table->decimal('total_taxable_benefits', 15, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('stafify_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stafify_fringe_benefits');
    }
};