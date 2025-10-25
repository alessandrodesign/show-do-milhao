<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            GameDifficultySeeder::class,
            CategorySeeder::class,

            // 10k perguntas (1000 cada), alternando natal/reveillon:
            GameQA_01_Natal_1000::class,
            GameQA_02_Reveillon_1000::class,
            GameQA_03_Natal_1000::class,
            GameQA_04_Reveillon_1000::class,
            GameQA_05_Natal_1000::class,
            GameQA_06_Reveillon_1000::class,
            GameQA_07_Natal_1000::class,
            GameQA_08_Reveillon_1000::class,
            GameQA_09_Natal_1000::class,
            GameQA_10_Reveillon_1000::class,
        ]);
    }
}
