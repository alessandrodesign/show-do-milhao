<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\GameState;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('current_step')->default(1); // 1..16
            $table->unsignedBigInteger('current_prize')->default(0);
            $table->unsignedBigInteger('secured_prize')->default(0); // pontos de segurança
            $table->enum('state', array_column(GameState::cases(),'value'))->default(GameState::RUNNING->value)->index();
            $table->boolean('lifeline_5050')->default(true);
            $table->boolean('lifeline_universitarios')->default(true);
            $table->boolean('lifeline_placas')->default(true);
            $table->unsignedTinyInteger('lifeline_pulo')->default(3); // nº de pulos
            $table->boolean('finished')->default(false);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
