<?php

namespace Database\Seeders;

use App\Models\Lifeline;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LifelinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
                     ['key' => '5050', 'name' => '50/50'],
                     ['key' => 'universitarios', 'name' => 'Universitários'],
                     ['key' => 'placas', 'name' => 'Placas'],
                     ['key' => 'pulo', 'name' => 'Pular']
                 ] as $l) Lifeline::firstOrCreate(['key' => $l['key']], $l);
    }
}
