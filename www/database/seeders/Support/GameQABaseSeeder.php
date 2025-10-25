<?php

namespace Database\Seeders\Support;

use App\Models\GameAnswer;
use App\Models\GameCategory;
use App\Models\GameDifficulty;
use App\Models\GameQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

abstract class GameQABaseSeeder extends Seeder
{
    protected int $categoryId;   // 1 = Natal, 2 = Réveillon
    protected int $total;        // total de perguntas a gerar neste seeder
    protected int $startSeq = 0; // deslocamento inicial da sequência
    protected ?GameQATemplateProvider $provider = null;

    public function run(): void
    {
        $this->ensureBaseData();

        $difficulties = GameDifficulty::orderBy('order')->get();
        $count = 0;
        $diffIndex = 0;

        $seq = $this->nextSequenceNumberForCategory($this->categoryId) + $this->startSeq;

        $provider = $this->provider ?? new GameQATemplateProvider();

        while ($count < $this->total) {
            $difficulty = $difficulties[$diffIndex % $difficulties->count()];
            $bundle = $provider->make($this->categoryId, $difficulty->order, $seq);

            $questionText = $bundle['question'];

            // Evita duplicidade exata
            $exists = GameQuestion::where('category_id', $this->categoryId)
                ->where('difficulty_id', $difficulty->id)
                ->where('question', $questionText)
                ->exists();

            if (!$exists) {
                DB::transaction(function () use ($difficulty, $questionText, $bundle) {
                    $q = GameQuestion::create([
                        'category_id'   => $this->categoryId,
                        'difficulty_id' => $difficulty->id,
                        'question'      => $questionText,
                        'hint'          => $bundle['hint'] ?? null,
                    ]);

                    // Monta alternativas
                    $answers = array_merge(
                        [['text' => $bundle['correct'], 'is_correct' => true]],
                        array_map(fn($w) => ['text' => $w, 'is_correct' => false], $bundle['wrongs'])
                    );
                    shuffle($answers);

                    foreach ($answers as $a) {
                        GameAnswer::create([
                            'question_id' => $q->id,
                            'text'        => $a['text'],
                            'is_correct'  => $a['is_correct'],
                        ]);
                    }
                });

                $count++;
            }

            $seq++;
            $diffIndex++;
        }

        echo static::class . " concluiu geração de {$count} perguntas para categoria {$this->categoryId}\n";
    }

    private function ensureBaseData(): void
    {
        GameCategory::firstOrCreate(['id' => 1], ['name' => 'Natal do Amor', 'description' => 'Natal do Amor'])->save();
        GameCategory::firstOrCreate(['id' => 2], ['name' => 'Reveilon do Amor', 'description' => 'Reveilon do Amor'])->save();

        for ($i = 1; $i <= 16; $i++) {
            GameDifficulty::updateOrCreate(
                ['order' => $i],
                ['name' => 'Pergunta ' . ($i === 16 ? 'Final' : $i), 'prize' => $this->defaultPrize($i)]
            );
        }
    }

    private function defaultPrize(int $order): float
    {
        return match ($order) {
            1 => 1000, 2 => 2000, 3 => 3000, 4 => 4000, 5 => 5000,
            6 => 10000, 7 => 20000, 8 => 30000, 9 => 50000, 10 => 10000,
            11 => 20000, 12 => 30000, 13 => 40000, 14 => 50000, 15 => 750000, 16 => 10000,
            default => 0,
        };
    }

    private function nextSequenceNumberForCategory(int $categoryId): int
    {
        $last = GameQuestion::where('category_id', $categoryId)->orderBy('id', 'desc')->first();
        if (!$last) return 1;

        if (preg_match('/#(\d+)$/', $last->question, $m)) {
            return (int)$m[1] + 1;
        }
        return GameQuestion::where('category_id', $categoryId)->count() + 1;
    }
}
