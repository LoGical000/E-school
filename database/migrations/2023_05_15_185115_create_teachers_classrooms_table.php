<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('teachers_classrooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedbigInteger('teacher_id');
            $table->foreign('teacher_id')->references('teacher_id')->on('teachers')
                ->onUpdate('cascade');
            $table->unsignedbigInteger('classroom_id');
            $table->foreign('classroom_id')->references('classroom_id')->on('classrooms')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('teachers_classrooms');
    }
};
