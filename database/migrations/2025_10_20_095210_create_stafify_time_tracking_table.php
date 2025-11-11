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
        Schema::create('stafify_time_tracking', function (Blueprint $table) {
            $table->id('record_id');
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->datetime('clock_in_time')->nullable();
            $table->datetime('clock_out_time')->nullable();
            $table->date('record_date');
            $table->decimal('total_hours', 5, 2)->nullable();
            $table->enum('status', ['pending', 'completed', 'incomplete', 'absent'])->default('pending');
            $table->string('location', 100)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('shift_id')->references('shift_id')->on('stafify_shifts');
            $table->foreign('user_id')->references('user_id')->on('stafify_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stafify_time_tracking');
    }
};