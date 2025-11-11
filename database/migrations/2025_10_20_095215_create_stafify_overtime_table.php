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
        Schema::create('stafify_overtime', function (Blueprint $table) {
            $table->id('ot_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->datetime('requested_date')->useCurrent();
            $table->date('ot_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('duration', 5, 2);
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->datetime('approved_date')->nullable();
            $table->text('approval_notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('stafify_users');
            $table->foreign('shift_id')->references('shift_id')->on('stafify_shifts');
            $table->foreign('approved_by')->references('user_id')->on('stafify_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stafify_overtime');
    }
};