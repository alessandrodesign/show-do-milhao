<?php

namespace Database\Seeders;

use App\Models\Difficulty;
use Illuminate\Database\Seeder;

class DifficultySeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['name' => 'Fácil', 'level' => 1],
            ['name' => 'Médio', 'level' => 2],
            ['name' => 'Difícil', 'level' => 3],
            ['name' => 'Muito Difícil', 'level' => 4],
            ['name' => 'Extremo', 'level' => 5],
        ];

        foreach ($levels as $item) {
            Difficulty::firstOrCreate(['level' => $item['level']], ['name' => $item['name']]);
        }
    }
}
