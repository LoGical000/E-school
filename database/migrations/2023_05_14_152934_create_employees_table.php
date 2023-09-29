<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{//
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            //$table->id();
            $table->bigIncrements('employee_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('job');
            $table->string('phone_number');
            $table->text('address');
            $table->text('details')->default('none');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
