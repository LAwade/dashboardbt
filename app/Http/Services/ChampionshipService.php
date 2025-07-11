<?php

namespace App\Http\Services;

use App\Models\Championship;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class ChampionshipService
{
    public function findByName($name)
    {
        try {
            $championships = Championship::whereRaw('name ILIKE ?', ["%{$name}%"])->get();
            return $championships;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao buscar o champeonato no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function findById($id)
    {
        try {
            $championship = Championship::findOrFail($id);
            return $championship;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao buscar o champeonato no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function findAll()
    {
        try {
            $championship = Championship::all();
            return $championship;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao buscar todos os campeonatos no banco de dados', [
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
            Log::channel('database_errors')->error('Erro ao buscar games por campeonato no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }
}