<?php

namespace Database\Seeders;

use App\Models\GameCategory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Natal do Amor',
            'Reveilon do Amor',
        ];

        foreach ($categories as $name) {
            GameCategory::firstOrCreate(['name' => $name], ['description' => $name]);
        }
    }
}
