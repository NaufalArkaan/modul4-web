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
            $table->string('type')->default('Anime'); // Anime, Movie, Series, dsb.
            $table->string('genre');
            $table->enum('status', ['Ongoing', 'Finished'])->default('Ongoing');
            $table->integer('episodes')->default(1);
            $table->decimal('score', 3, 1)->default(0.0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};