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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('paddle_plan_id')->nullable();
            $table->string('uuid')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 12, 2);
            $table->decimal('price_yearly', 12, 2);
            $table->integer('instance_limit')->nullable();
            $table->integer('duration_days')->nullable(); // Ajout pour la période d'essai
            $table->boolean('is_free')->default(false);
            $table->boolean('is_default')->default(false);
            $table->json('features');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
