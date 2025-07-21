<?php

namespace Database\Seeders;

use App\Models\Championship;
use App\Models\Court;
use App\Models\Game;
use App\Models\Permission;
use App\Models\Status;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GeneralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * PERMISSÕES
         */
        $permissions = [
            ['name' => 'Administrador'],
            ['name' => 'Juiz Geral'],
            ['name' => 'Juiz Quadra'],
            ['name' => 'Jogador']
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
            ]);
        }
        
        /**
         * USUÁRIOS
         */
        User::factory()->create([
            'id' => Str::uuid(),
            'name' => 'Lucas Awade',
            'email' => 'lucasawade46@gmail.com',
            'password' => bcrypt('12345678'), // Senha padrão para o usuário
            'permission_id' => 1, // ID do permissionário de administrador
        ]);

        $statuses = [
            ['name' => 'AGUARDANDO'],
            ['name' => 'EM ANDAMENTO'],
            ['name' => 'CONFIRMADO'],
            ['name' => 'CANCELADO'],
            ['name' => 'FINALIZADO'],
        ];

        foreach ($statuses as $status) {
            Status::create([
                'name' => $status['name'],
            ]);
        }

        /**
         * CAMPEONATOS
         */
        Championship::create([
            'id' => Str::uuid(),
            'name' => 'Campeonato de Verão',
            'description' => 'Campeonato de verão na Arena.',
            'date' => '2025-08-28',
            'status_id' => 1, 
        ]);

        $championship = Championship::first();

        /**
         * QUADRAS
         */
        $courts = [
            ['name' => 'Quadra 1', 'number' => 1],
            ['name' => 'Quadra 2', 'number' => 2],
            ['name' => 'Quadra 3', 'number' => 3],
            ['name' => 'Quadra 4', 'number' => 4],
            ['name' => 'Quadra 5', 'number' => 5],
            ['name' => 'Quadra 6', 'number' => 6],
            ['name' => 'Quadra 7', 'number' => 7],
            ['name' => 'Quadra 8', 'number' => 8],
            ['name' => 'Quadra 9', 'number' => 9],
            ['name' => 'Quadra 10', 'number' => 10],
            ['name' => 'Quadra 11', 'number' => 11],
            ['name' => 'Quadra 12', 'number' => 12],
        ];

        foreach ($courts as $court) {
            Court::create([
                'id' => Str::uuid(),
                'name' => $court['name'],
                'number' => $court['number']
            ]);
        }

        /**
         * TIMES
         */
        $teams = [
            ['player_one' => 'João Pedro', 'player_two' => 'Carlos Ferreira', 'championship_id' => $championship->id],
            ['player_one' => 'Jorge Silva', 'player_two' => 'Mariano Costa', 'championship_id' => $championship->id],
            ['player_one' => 'Lucas Henrique', 'player_two' => 'Pedro Henrique', 'championship_id' => $championship->id],
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

        foreach ($teams as $team) {
            Team::create($team);
        }

        /**
         * JOGOS
         */
        $quadra_um = Court::where('number', 1)->first();
        $quadra_dois = Court::where('number', 2)->first();
        $quadra_tres = Court::where('number', 3)->first();
        $quadra_quatro = Court::where('number', 4)->first();
        
        
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
