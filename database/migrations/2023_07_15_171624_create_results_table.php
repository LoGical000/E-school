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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->float('result');
            $table->integer('grade_id');
            $table->string('schoolyear');
            $table->unsignedbigInteger('student_id');
            $table->foreign('student_id')->references('student_id')->on('students')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unique(['student_id', 'schoolyear']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
