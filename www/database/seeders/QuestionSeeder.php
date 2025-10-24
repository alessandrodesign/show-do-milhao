<?php

namespace Database\Seeders;

use App\Models\Alternative;
use App\Models\Category;
use App\Models\Difficulty;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $cat = Category::firstOrCreate(['name' => 'Conhecimentos Gerais'], ['slug' => 'geral']);
        $dif = Difficulty::firstOrCreate(['level' => 1], ['name' => 'Fácil']);

        $qs = [
            'Qual é o maior planeta do sistema solar?'      => ['A' => 'Terra', 'B' => 'Júpiter', 'C' => 'Saturno', 'D' => 'Marte', 'correct' => 'B'],
            'Quem descobriu o Brasil?'                      => ['A' => 'Pedro Álvares Cabral', 'B' => 'Cristóvão Colombo', 'C' => 'Vasco da Gama', 'D' => 'Dom Pedro I', 'correct' => 'A'],
            'Quantos continentes existem no planeta Terra?' => ['A' => '5', 'B' => '6', 'C' => '7', 'D' => '8', 'correct' => 'C'],
        ];

        foreach ($qs as $st => $alts) {
            $q = Question::create(['category_id' => $cat->id, 'difficulty_id' => $dif->id, 'statement' => $st]);
            foreach (['A', 'B', 'C', 'D'] as $l) {
                Alternative::create([
                    'question_id' => $q->id,
                    'letter'      => $l,
                    'text'        => $alts[$l],
                    'is_correct'  => ($l === $alts['correct']),
                ]);
            }
        }
    }
}
