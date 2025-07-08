<?php

namespace Database\Seeders;

use App\Models\Championship;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ChampionshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Championship::create([
            'id' => Str::uuid(),
            'name' => 'Campeonato de Verão',
            'description' => 'Campeonato de verão na Arena.',
            'date' => '2025-08-28',
            'status_id' => 1, // Aguardando
        ]);
    }
}
