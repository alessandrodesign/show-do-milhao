<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('game_scores', function (Blueprint $t) {
            $t->unsignedInteger('response_ms')->nullable()->after('correct');
        });
    }

    public function down(): void
    {
        Schema::table('game_scores', function (Blueprint $t) {
            $t->dropColumn('response_ms');
        });
    }
};
