<?php

namespace Database\Seeders;

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
         $teams = [
            ['player_one' => 'João Pedro', 'player_two' => 'Carlos Ferreira'],
            ['player_one' => 'Jorge Silva', 'player_two' => 'Mariano Costa'],
            ['player_one' => 'Lucas Almeida', 'player_two' => 'Pedro Henrique'],
            ['player_one' => 'Fernando Lima', 'player_two' => 'Roberto Silva'],
            ['player_one' => 'Francisco Santos', 'player_two' => 'Ricardo Oliveira'],
            ['player_one' => 'Gabriel Rocha', 'player_two' => 'Paulo Martins'],
            ['player_one' => 'Ítalo Souza', 'player_two' => 'Thiago Pereira'],
            ['player_one' => 'Rafael Gomes', 'player_two' => 'Marcelo Alves'],
            ['player_one' => 'Alcides Pedro', 'player_two' => 'Sabino Ferreira'],
            ['player_one' => 'Bruno Silva', 'player_two' => 'Eduardo Costa'],
            ['player_one' => 'Otávio Almeida', 'player_two' => 'Henrique Freitas'],
            ['player_one' => 'Gustavo Lima', 'player_two' => 'Kleberson Alfredo'],
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
