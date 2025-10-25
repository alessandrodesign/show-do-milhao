<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('game_difficulties', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->decimal('prize', 12, 2)->default(0);
            $table->unsignedTinyInteger('order')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_difficulties');
    }
};
