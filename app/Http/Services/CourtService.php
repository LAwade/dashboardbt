<?php

namespace App\Http\Services;

use App\Models\Court;
use App\Models\Game;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CourtService
{
    public function findAllWithGames()
    {
        try {
            $courts = DB::table('courts as c')
                ->select(
                    'c.id',
                    'c.name',
                    'c.number',
                    'c.enable',
                    DB::raw('COUNT(g.id) as total_games'),
                    DB::raw("COUNT(g.id) FILTER (WHERE g.status_id = 1) as pending"),
                    DB::raw("COUNT(g.id) FILTER (WHERE g.status_id = 2) as in_progress"),
                    DB::raw("COUNT(g.id) FILTER (WHERE g.status_id = 5) as finished")
                )
                ->leftJoin('games as g', 'c.id', '=', 'g.court_id')
                ->groupBy('c.id', 'c.name', 'c.number', 'c.enable')
                ->orderBy('c.number')
                ->get();

            return $courts;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a cidade no banco de dados', [
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
            $courts = Court::orderBy('number')->get();
            return $courts;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
    }

    public function findByName(string $name)
    {
        try {
            $courts = Court::where('name', 'ILIKE', "%$name%")->orderBy('name')->get();
            return $courts;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
    }

    public function findById(string $id)
    {
        try {
            $court = Court::findOrFail($id);
            return $court;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        } 
        return collect();
    }

    public function findCourtByGameId(string $id){
        try {
            $game = Game::find($id);
            if(!$game){
                return collect();
            }
            return Court::find($game->court_id);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a cidade no banco de dados', [
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
            $court = Court::create($data);
            return $court;
        } catch (\Exception $e) {
            Log::error('Erro ao criar a quadra no banco de dados', [
                'exception' => $e->getMessage(),
                'data' => $data
            ]);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro a quadra no banco de dados', [
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
            $court = Court::find($id);
            if (!$court) {
                return collect();
            }
            return $court->update($data);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao atualizar a quadra no banco de dados', [
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
            $court = Court::findOrFail($id);
            return $court->delete();
        } catch (QueryException $e) {
            Log::error('Erro ao excluir a quadra no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
    }
}
