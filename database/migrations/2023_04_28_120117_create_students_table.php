<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            //$table->id();
            $table->bigIncrements('student_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('religion');
            //$table->string('email')->unique();
            //$table->string('password');
            $table->date('date_of_birth');
            $table->text('address');
            $table->text('details')->default('none');
            $table->integer('grade_id');

            $table->integer('gender_id');

            $table->unsignedBigInteger('parent_id');
            $table->foreign('parent_id')->references('parent_id')->on('parents')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('status');

            $table->timestamps();
        });
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
