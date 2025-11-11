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
        Schema::create('performance_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('evaluation_date');
            $table->string('evaluator_name');
            $table->string('evaluation_type', 100);
            $table->text('remarks')->nullable();
            $table->integer('overall_score')->default(0);
            $table->integer('job_knowledge')->default(0);
            $table->integer('productivity')->default(0);
            $table->integer('work_quality')->default(0);
            $table->integer('technical_skills')->default(0);
            $table->integer('work_consistency')->default(0);
            $table->integer('enthusiasm')->default(0);
            $table->integer('cooperation')->default(0);
            $table->integer('attitude')->default(0);
            $table->integer('initiative')->default(0);
            $table->integer('work_relations')->default(0);
            $table->integer('creativity')->default(0);
            $table->integer('punctuality')->default(0);
            $table->integer('attendance')->default(0);
            $table->integer('dependability')->default(0);
            $table->integer('written_comm')->default(0);
            $table->integer('verbal_comm')->default(0);
            $table->integer('appearance')->default(0);
            $table->integer('uniform')->default(0);
            $table->integer('personal_hygiene')->default(0);
            $table->integer('tidiness')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('stafify_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_evaluations');
    }
};