<?php

namespace Database\Seeders;

use App\Models\GameDifficulty;
use Illuminate\Database\Seeder;

class GameDifficultySeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['order' => 1, 'name' => 'Pergunta 1',  'prize' => 1000],
            ['order' => 2, 'name' => 'Pergunta 2',  'prize' => 2000],
            ['order' => 3, 'name' => 'Pergunta 3',  'prize' => 3000],
            ['order' => 4, 'name' => 'Pergunta 4',  'prize' => 4000],
            ['order' => 5, 'name' => 'Pergunta 5',  'prize' => 5000],
            ['order' => 6, 'name' => 'Pergunta 6',  'prize' => 10000],
            ['order' => 7, 'name' => 'Pergunta 7',  'prize' => 20000],
            ['order' => 8, 'name' => 'Pergunta 8',  'prize' => 30000],
            ['order' => 9, 'name' => 'Pergunta 9',  'prize' => 50000],
            ['order' => 10,'name' => 'Pergunta 10', 'prize' => 100000],
            ['order' => 11,'name' => 'Pergunta 11', 'prize' => 200000],
            ['order' => 12,'name' => 'Pergunta 12', 'prize' => 300000],
            ['order' => 13,'name' => 'Pergunta 13', 'prize' => 400000],
            ['order' => 14,'name' => 'Pergunta 14', 'prize' => 500000],
            ['order' => 15,'name' => 'Pergunta 15', 'prize' => 750000],
            ['order' => 16,'name' => 'Pergunta Final', 'prize' => 1000000],
        ];

        foreach ($levels as $level) {
            GameDifficulty::updateOrCreate(['order' => $level['order']], $level);
        }
    }
}
