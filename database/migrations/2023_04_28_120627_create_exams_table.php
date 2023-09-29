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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedbigInteger('student_id');
            $table->unsignedbigInteger('subject_id');
            $table->unsignedbigInteger('type_id');
            $table->foreign('student_id')->references('student_id')->on('students')
                ->onUpdate('cascade');

            $table->foreign('subject_id')->references('subject_id')->on('subjects')
                ->onUpdate('cascade');

            $table->foreign('type_id')->references('type_id')->on('exams_type')
                ->onUpdate('cascade');

            $table->float('mark');
            $table->date('date');
            $table->string('schoolyear');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
