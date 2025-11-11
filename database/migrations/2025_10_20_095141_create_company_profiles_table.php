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
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id('company_id');
            $table->string('company_name');
            $table->string('company_address');
            $table->string('company_phone');
            $table->string('company_email');
            $table->string('company_logo')->default('default-company-logo.png');
            $table->string('timezone')->default('UTC');
            $table->integer('week_start')->default(0)->comment('0=Sunday, 1=Monday, etc.');
            $table->string('year_type')->default('calendar')->comment('calendar or fiscal');
            $table->integer('fiscal_start_month')->nullable();
            $table->integer('fiscal_start_day')->nullable();
            $table->integer('fiscal_end_month')->nullable();
            $table->integer('fiscal_end_day')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
};