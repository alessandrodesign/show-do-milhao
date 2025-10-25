<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('game_scores', function (Blueprint $table) {
            $table->unsignedInteger('questions_total')->default(0)->after('total_wrong');
            $table->unsignedInteger('lifelines_used')->default(0)->after('questions_total');
            $table->decimal('avg_response_time', 8, 2)->default(0)->after('lifelines_used');
        });
    }

    public function down(): void
    {
        Schema::table('game_scores', function (Blueprint $table) {
            $table->dropColumn(['questions_total', 'lifelines_used', 'avg_response_time']);
        });
    }
};
