<?php

namespace App\Http\Services;

use App\Models\Championship;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        }
        return collect();
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
        }

        return collect();
    }

    public function findAll()
    {
        try {
            $championship = Championship::orderBy('date', 'asc')->get();
            return $championship;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao buscar todos os campeonatos no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
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
        }
        return collect();
    }

    public function create(array $data)
    {
        try {
            $data['id'] = Str::uuid();
            $championship = Championship::create($data);
            return $championship;
        } catch (\Exception $e) {
            Log::error('Erro ao criar o campeonato no banco de dados', [
                'exception' => $e->getMessage(),
                'data' => $data
            ]);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao criar o campeonato no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
    }

    public function update(string $id, array $data)
    {
        try {
            $championship = Championship::find($id);
            if (!$championship) {
                return collect();
            }
            return $championship->update($data);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao atualizar o campeonato no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
    }

    public function delete(string $id)
    {
        try {
            $championship = Championship::findOrFail($id);
            return $championship->delete();
        } catch (QueryException $e) {
            Log::error('Erro ao excluir o campeonato no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
    }
}
