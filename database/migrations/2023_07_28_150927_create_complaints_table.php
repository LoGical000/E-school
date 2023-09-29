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
        Schema::create('complaints', function (Blueprint $table) {
            $table->bigIncrements('complaint_id');
            $table->unsignedbigInteger('parent_id');
            $table->foreign('parent_id')->references('parent_id')->on('parents');
            $table->date('date');
            $table->text('description');
            $table->enum('status', ['pending', 'resolved'])->default('pending');
            $table->timestamps();

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
