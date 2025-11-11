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
        Schema::create('sales_toolkit', function (Blueprint $table) {
            $table->id('sales_id');
            $table->unsignedBigInteger('user_id');
            $table->string('sales_title');
            $table->text('form_url')->nullable();
            $table->text('response_url')->nullable();
            $table->string('icon')->default('communication.gif');
            $table->enum('type', ['Form', 'Sheet', 'Video', 'Slides', 'Folder', 'Form+Sheet', 'Website']);
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_toolkit');
    }
};