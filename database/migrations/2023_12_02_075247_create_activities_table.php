<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->timestamp('deadline');
            $table->boolean('isNotificate')->default(true);
            $table->enum('category', ['task', 'event']);
            $table->timestamps();
            $table->foreignId('userId')->references('id')->on('users');
            // $table->foreignId('categoryId')->references('id')->on('categories');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
