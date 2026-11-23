<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->enum('type', ['anime', 'manga']);
            $table->string('genre');
            $table->decimal('score', 5, 2)->default(0);
            $table->unsignedInteger('episodes')->nullable();
            $table->unsignedInteger('chapters')->nullable();
            $table->unsignedInteger('volumes')->nullable();
            $table->string('cover_image', 500)->nullable();
            $table->string('site_url', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};