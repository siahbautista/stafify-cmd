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
        Schema::table('stafify_settings', function (Blueprint $table) {
            $table->string('company')->nullable()->after('setting_id');
            $table->dropUnique(['setting_key']);
            $table->unique(['company', 'setting_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stafify_settings', function (Blueprint $table) {
            $table->dropUnique(['company', 'setting_key']);
            $table->unique('setting_key');
            $table->dropColumn('company');
        });
    }
};
