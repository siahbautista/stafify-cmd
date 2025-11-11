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
        Schema::table('stafify_shifts', function (Blueprint $table) {
            $table->boolean('include_break')->default(false)->after('notes');
            $table->integer('break_duration_minutes')->nullable()->after('include_break');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stafify_shifts', function (Blueprint $table) {
            $table->dropColumn(['include_break', 'break_duration_minutes']);
        });
    }
};
