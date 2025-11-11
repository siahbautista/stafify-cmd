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
        Schema::create('company_branches', function (Blueprint $table) {
            $table->id('branch_id');
            $table->unsignedBigInteger('company_id');
            $table->string('branch_location');
            $table->text('branch_address');
            $table->string('branch_phone', 50);
            $table->boolean('is_headquarters')->default(false);
            $table->timestamps();

            $table->foreign('company_id')->references('company_id')->on('company_profiles')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_branches');
    }
};