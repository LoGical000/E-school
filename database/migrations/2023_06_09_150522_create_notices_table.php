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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
//
            $table->unsignedbigInteger('student_id');
            $table->foreign('student_id')->references('student_id')->on('students')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('day');
            $table->string('type');
            $table->text('content');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
