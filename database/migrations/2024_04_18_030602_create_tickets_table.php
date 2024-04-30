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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('service_location');
            $table->string('unit');
            $table->string('request');
            $table->enum('priority_level', ['High', 'Mid', 'Low']);
            $table->date('deadline');
            $table->string('description');
            $table->string('file_path')->nullable();
            $table->enum('status', ['Open','In Progress', 'Closed'])->default('Open');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
