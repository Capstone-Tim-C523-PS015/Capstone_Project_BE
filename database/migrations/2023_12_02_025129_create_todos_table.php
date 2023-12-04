<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->timestamp('deadline');
            $table->enum('status', ['menunggu', 'dikerjakan', 'telat', 'selesai', 'revisi']);
            $table->timestamps();
            $table->foreignId('userId')->references('id')->on('users');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
