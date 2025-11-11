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
        Schema::create('stafify_events', function (Blueprint $table) {
            $table->id('event_id');
            $table->unsignedBigInteger('created_by');
            $table->string('event_title');
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('event_location', 100)->nullable();
            $table->string('event_type', 50)->nullable();
            $table->string('event_visibility', 20)->default('all');
            $table->text('event_description')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('user_id')->on('stafify_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stafify_events');
    }
};