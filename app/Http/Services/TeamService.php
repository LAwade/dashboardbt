<?php

namespace App\Http\Services;

use App\Models\Court;
use App\Models\Game;
use App\Models\Team;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeamService
{
    public function findByName(string $name){
        try {
            $player = Team::whereRaw('player_one ILIKE ?', ["%{$name}%"])
              ->orWhereRaw('player_two ILIKE ?', ["%{$name}%"])
              ->get();
            return $player;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao buscar jogador no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function findById(string $id){
        try {
            $player = Team::find($id);
            return $player;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao buscar jogador no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}