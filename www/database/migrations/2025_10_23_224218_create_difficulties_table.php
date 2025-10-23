<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('difficulties', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedTinyInteger('level')->unique()->comment('1=fácil ... 5=muito difícil');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('difficulties');
    }
};
