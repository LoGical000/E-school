<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('parents', function (Blueprint $table) {
            //$table->id();
            $table->bigIncrements('parent_id');
            $table->string('father_first_name');
            $table->string('father_last_name');
            $table->string('father_phone_number');
            $table->string('mother_first_name');
            $table->string('mother_last_name');
            $table->string('mother_phone_number');
            //$table->string('email')->unique()->nullable();
            //$table->string('password')->nullable();
            $table->string('national_id')->unique();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            //$table->string('mother_national_id')->unique();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
