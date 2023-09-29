<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedbigInteger('classroom_id');
            $table->foreign('classroom_id')->references('classroom_id')->on('classrooms')
                ->onUpdate('cascade');
            $table->integer('day_number');

            $table->unsignedbigInteger('first_subject');
            $table->foreign('first_subject')->references('subject_id')->on('subjects')
                ->onUpdate('cascade');

            $table->unsignedbigInteger('second_subject');
            $table->foreign('second_subject')->references('subject_id')->on('subjects')
                ->onUpdate('cascade');

            $table->unsignedbigInteger('third_subject');
            $table->foreign('third_subject')->references('subject_id')->on('subjects')
                ->onUpdate('cascade');


            $table->unsignedbigInteger('fourth_subject');
            $table->foreign('fourth_subject')->references('subject_id')->on('subjects')
                ->onUpdate('cascade');

            $table->unsignedbigInteger('fifth_subject');
            $table->foreign('fifth_subject')->references('subject_id')->on('subjects')
                ->onUpdate('cascade');

            $table->unsignedbigInteger('sixth_subject');
            $table->foreign('sixth_subject')->references('subject_id')->on('subjects')
                ->onUpdate('cascade');

            $table->unsignedbigInteger('seventh_subject')->default(1);
            $table->foreign('seventh_subject')->references('subject_id')->on('subjects')
                ->onUpdate('cascade');






            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
