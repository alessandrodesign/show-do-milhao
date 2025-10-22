<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionsDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $q = Question::create([
            'title'=>'Capitais', 'statement'=>'Qual é a capital do Brasil?', 'difficulty'=>'easy', 'active'=>true
        ]);
        Answer::insert([
            ['question_id'=>$q->id,'label'=>'A','text'=>'Rio de Janeiro','is_correct'=>false],
            ['question_id'=>$q->id,'label'=>'B','text'=>'São Paulo','is_correct'=>false],
            ['question_id'=>$q->id,'label'=>'C','text'=>'Brasília','is_correct'=>true],
            ['question_id'=>$q->id,'label'=>'D','text'=>'Salvador','is_correct'=>false],
        ]);
    }

}
