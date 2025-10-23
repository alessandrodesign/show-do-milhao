<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Natal do Amor', 'Ciências', 'História', 'Geografia',
            'Entretenimento', 'Esportes', 'Conhecimentos Gerais',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name], ['slug' => Str::slug($name)]);
        }
    }
}
