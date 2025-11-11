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
        Schema::create('talent_management_resources', function (Blueprint $table) {
            $table->id();
            $table->string('resource_key')->unique();
            $table->string('title');
            $table->string('type'); // document, spreadsheet, form
            $table->text('url');
            $table->text('form_url')->nullable();
            $table->string('icon_path')->nullable();
            $table->string('icon_lordicon')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talent_management_resources');
    }
};