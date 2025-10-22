<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('lifelines_used', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lifeline_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('used_on_step');
            $table->json('payload')->nullable(); // detalhes (ex.: alternativas removidas)
            $table->timestamps();

            $table->unique(['game_id','lifeline_id','used_on_step']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lifelines_used');
    }
};
