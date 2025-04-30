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
        Schema::create('tutoriels', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('category');
            $table->string('Titre');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('video_url')->nullable();
            $table->string('type')->nullable();
            $table->string('author')->nullable();
            $table->string('visible')->default('Non');
            $table->string('tag')->nullable();
            $table->integer('row')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutoriels');
    }
};
