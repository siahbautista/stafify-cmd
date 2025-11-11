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
        Schema::table('talent_toolkit', function (Blueprint $table) {
            // Change enum to string to allow new type
            $table->string('type', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talent_toolkit', function (Blueprint $table) {
            // Revert back to enum (restore original enum values)
            $table->enum('type', ['Form', 'Sheet', 'Video', 'Slides', 'Folder', 'Form+Sheet', 'Website'])->change();
        });
    }
};
