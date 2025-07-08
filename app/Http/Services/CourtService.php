<?php

namespace App\Http\Services;

use App\Models\Court;
use App\Models\Game;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        } catch (\Exception $e) {
            return false;
        }
    }

    public function findAll()
    {
        try {
            $courts = Court::all();
            return $courts;
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
        } catch (\Exception $e) {
            logger(['error' => $e->getMessage()]);
            return false;
        }
    }

    public function findCourtByGameId(string $id){
        try {
            $game = Game::find($id);
            if(!$game){
                return null;
            }
            return Court::find($game->court_id);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
    }

    public function create(array $data)
    {
        return Court::create($data);
    }

    public function update(Court $court, array $data)
    {
        $court->update($data);
        return $court;
    }

    public function delete(Court $court)
    {
        return $court->delete();
    }
}
