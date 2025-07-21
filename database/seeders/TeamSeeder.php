<?php

namespace Database\Seeders;

use App\Models\Championship;
use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the statuses to be seeded
        $championship = Championship::first();

        $teams = [
            ['player_one' => 'João Pedro', 'player_two' => 'Carlos Ferreira', 'championship_id' => $championship->id],
            ['player_one' => 'Jorge Silva', 'player_two' => 'Mariano Costa', 'championship_id' => $championship->id],
            ['player_one' => 'Lucas Almeida', 'player_two' => 'Pedro Henrique', 'championship_id' => $championship->id],
            ['player_one' => 'Fernando Lima', 'player_two' => 'Roberto Silva', 'championship_id' => $championship->id],
            ['player_one' => 'Francisco Santos', 'player_two' => 'Ricardo Oliveira', 'championship_id' => $championship->id],
            ['player_one' => 'Gabriel Rocha', 'player_two' => 'Paulo Martins', 'championship_id' => $championship->id],
            ['player_one' => 'Ítalo Souza', 'player_two' => 'Thiago Pereira', 'championship_id' => $championship->id],
            ['player_one' => 'Rafael Gomes', 'player_two' => 'Marcelo Alves', 'championship_id' => $championship->id],
            ['player_one' => 'Alcides Pedro', 'player_two' => 'Sabino Ferreira', 'championship_id' => $championship->id],
            ['player_one' => 'Bruno Silva', 'player_two' => 'Eduardo Costa', 'championship_id' => $championship->id],
            ['player_one' => 'Otávio Almeida', 'player_two' => 'Henrique Freitas', 'championship_id' => $championship->id],
            ['player_one' => 'Gustavo Lima', 'player_two' => 'Kleberson Alfredo', 'championship_id' => $championship->id],
        ];

        // Insert the teams into the database
        foreach ($teams as $team) {
            Team::create([
                'player_one' => $team['player_one'],
                'player_two' => $team['player_two']
            ]);
        }
    }
}
