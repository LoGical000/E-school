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
        Schema::create('exam_schedule__exam_type_pivot', function (Blueprint $table) {
            $table->id();
//
            $table->unsignedbigInteger('type_id');
            $table->foreign('type_id')->references('type_id')->on('exams_type')
                ->onUpdate('cascade');


            $table->unsignedbigInteger('exam_schedule_id');
            $table->foreign('exam_schedule_id')->references('exam_schedule_id')->on('exam_schedules')
                ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
