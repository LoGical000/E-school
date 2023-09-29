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
        Schema::create('teachers', function (Blueprint $table) {
            //$table->id();
            $table->bigIncrements('teacher_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number');
           // $table->string('urgent_phone_number');
            //$table->string('email')->unique();//
            //$table->string('password')->nullable();
            $table->text('address');
            $table->text('details')->default('none');
            $table->unsignedbigInteger('subject_id');
            $table->foreign('subject_id')->references('subject_id')->on('subjects')
                ->onUpdate('cascade');



            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
