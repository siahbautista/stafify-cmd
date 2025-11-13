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
        Schema::create('company_positions', function (Blueprint $table) {
            $table->id('position_id');
            $table->unsignedBigInteger('company_id');
            $table->string('position_name');

            // Foreign key constraint
            $table->foreign('company_id')
                  ->references('company_id')
                  ->on('company_profiles')
                  ->onDelete('cascade');

            // Make position name unique *per company*
            $table->unique(['company_id', 'position_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_positions');
    }
};