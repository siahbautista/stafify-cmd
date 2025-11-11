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
        Schema::create('stafify_users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('user_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('user_email')->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->integer('user_pin')->nullable();
            $table->string('company')->nullable();
            $table->string('user_dept')->nullable();
            $table->string('user_position')->nullable();
            $table->integer('access_level')->default(1);
            $table->integer('is_admin')->default(0);
            $table->string('address')->nullable();
            $table->string('country', 100)->nullable();
            $table->string('country_code', 10)->nullable();
            $table->date('employment_date')->nullable();
            $table->string('branch_location', 100)->nullable();
            $table->string('engagement_status', 50)->nullable();
            $table->string('user_status', 50)->nullable();
            $table->string('user_type', 50)->nullable();
            $table->string('wage_type', 50)->nullable();
            $table->boolean('sil_status')->default(false);
            $table->boolean('statutory_benefits')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stafify_users');
    }
};