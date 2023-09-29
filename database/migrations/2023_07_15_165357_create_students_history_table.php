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
        Schema::create('students_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedbigInteger('student_id');
            $table->foreign('student_id')->references('student_id')->on('students')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->text('address');
            $table->text('details');
            $table->integer('grade_id');
            $table->string('status');
            $table->string('room_number');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students_history');
    }
};
