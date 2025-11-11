<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('user_name')->unique();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('full_name');
            $table->string('user_email')->unique();
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('country')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('user_pin')->nullable();
            $table->string('company')->nullable();
            $table->string('user_dept')->default('Unassigned');
            $table->string('user_position')->default('Unassigned');
            $table->string('user_password');
            $table->tinyInteger('is_archived')->default(0);
            $table->tinyInteger('access_level')->default(0); // 0=pending, 1=admin, 2=client, 3=user
            $table->string('profile_picture')->default('default.png');
            $table->string('temp_name')->nullable();
            $table->date('employment_date')->nullable();
            $table->string('branch_location')->nullable();
            $table->string('engagement_status')->nullable();
            $table->string('user_status')->nullable();
            $table->string('user_type')->nullable();
            $table->string('wage_type')->nullable();
            $table->tinyInteger('sil_status')->default(0);
            $table->tinyInteger('statutory_benefits')->default(0);
            $table->string('drive_folder_id')->nullable();
            $table->text('drive_folder_link')->nullable();
            $table->tinyInteger('is_verified')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
