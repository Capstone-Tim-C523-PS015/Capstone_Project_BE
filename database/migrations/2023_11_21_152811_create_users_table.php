<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $pfImage = env('APP_URL').'/profile/default.png';
        Schema::create('users', function (Blueprint $table) use ($pfImage){
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->longText('description')->nullable();
            $table->string('profileImage')->default($pfImage);
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
