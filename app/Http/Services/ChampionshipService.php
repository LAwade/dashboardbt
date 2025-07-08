<?php

namespace App\Http\Services;

use App\Models\Championship;
use App\Models\Court;
use App\Models\Game;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChampionshipService
{
    public function findAll()
    {
        try {
            $championship = Championship::all();
            return $championship;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function findChampionshipAndGames()
    {
        try {
            $championships = Championship::withCount('games')->get();
            return $championships;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}