<?php

namespace Database\Seeders;

use App\Models\Championship;
use App\Models\Court;
use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $quadra_um = Court::where('number', 1)->first();
        $quadra_dois = Court::where('number', 2)->first();
        $quadra_tres = Court::where('number', 3)->first();
        $quadra_quatro = Court::where('number', 4)->first();
        $championship = Championship::first();
        
        $games = [
            ['team_one' => 1, 'team_two' => 2, 'status_id' => 1, 'category' => "MASCULINO C", 'round' => 'GRUPOS', 'championship_id' => $championship->id, 'court_id' => $quadra_um->id, 'schedule' => '2025-08-28 13:00:00'],
            ['team_one' => 3, 'team_two' => 4, 'status_id' => 1, 'category' => "MASCULINO C", 'round' => 'GRUPOS', 'championship_id' => $championship->id, 'court_id' => $quadra_um->id, 'schedule' => '2025-08-28 14:00:00'],
            ['team_one' => 5, 'team_two' => 6, 'status_id' => 1, 'category' => "MASCULINO C", 'round' => 'GRUPOS', 'championship_id' => $championship->id, 'court_id' => $quadra_um->id, 'schedule' => '2025-08-28 15:00:00'],
            ['team_one' => 7, 'team_two' => 8, 'status_id' => 1, 'category' => "MASCULINO C", 'round' => 'GRUPOS', 'championship_id' => $championship->id, 'court_id' => $quadra_dois->id, 'schedule' => '2025-08-28 16:00:00'],
            ['team_one' => 9, 'team_two' => 10, 'status_id' => 1, 'category' => "MASCULINO C", 'round' => 'GRUPOS', 'championship_id' => $championship->id, 'court_id' => $quadra_tres->id, 'schedule' => '2025-08-28 17:00:00'],
            ['team_one' => 11, 'team_two' => 12, 'status_id' => 1, 'category' => "MASCULINO C", 'round' => 'GRUPOS', 'championship_id' => $championship->id, 'court_id' => $quadra_quatro->id, 'schedule' => '2025-08-28 18:00:00'],
        ];

        foreach ($games as $game) {
            Game::create([
                'team_one' => $game['team_one'],
                'team_two' => $game['team_two'],
                'status_id' => $game['status_id'],
                'championship_id' => $game['championship_id'],
                'court_id' => $game['court_id'],
                'schedule' => $game['schedule'],
                'category' => $game['category'],
                'round' => $game['round'],
            ]);
        }
    }
}
