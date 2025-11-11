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
        Schema::create('stafify_shifts', function (Blueprint $table) {
            $table->id('shift_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('assigned_by');
            $table->date('shift_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('shift_type', 50)->nullable();
            $table->string('location', 100)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('ot_modified')->default(false);
            $table->unsignedBigInteger('ot_modified_by')->nullable();
            $table->datetime('ot_modified_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('stafify_users');
            $table->foreign('assigned_by')->references('user_id')->on('stafify_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stafify_shifts');
    }
};