<?php

namespace App\Http\Services;

use App\Models\Game;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GameService
{

    public function findById(string $id)
    {
        try {
            $game = Game::with(['teamOne', 'teamTwo', 'status', 'championship', 'setResults'])
                ->where('id', $id)->first();
            return $game;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error(__FUNCTION__ . ' - Erro ao buscar o game cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
    }

    public function findWithTeamByCourt(string $id)
    {
        try {
            $games = Game::with(['teamOne', 'teamTwo', 'status', 'championship', 'setResults'])
                ->where('court_id', $id)
                ->whereHas('championship', function ($query) {
                    $query->where('status_id', 2);
                })
                ->orderBy('schedule', 'asc')
                ->get();

            return $games;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error(__FUNCTION__ . ' - Erro ao buscar o game cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
    }

    public function findFirstGameByCourt(string $id)
    {
        try {
            $game =  Game::with(['teamOne', 'teamTwo', 'status', 'championship', 'setResults'])
                ->where('court_id', $id)
                ->whereIn('status_id', [2, 1, 5])
                ->orderByRaw("
                CASE
                    WHEN status_id = 2 THEN 1
                    WHEN status_id = 1 THEN 2
                    WHEN status_id = 5 THEN 3
                    ELSE 4
                END
            ")
                ->orderBy('schedule', 'asc') // garante que o status 5 traga o último finalizado
                ->first();

            return $game;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error(__FUNCTION__ . ' - Erro ao buscar o game no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
    }

    public function findGameByCourtStatus($court, $status)
    {
        try {
            return Game::where('court_id', $court)->where('status_id', $status)->get();
        } catch (QueryException $e) {
            Log::channel('database_errors')->error(__FUNCTION__ . ' - Erro ao buscar o game no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
    }

    public function findAllGamesBySchedule($championship_id, $schedule = null, $paginate = false)
    {
        try {
            $query = Game::with(['teamOne', 'teamTwo', 'status', 'championship', 'court', 'setResults'])
                ->where("championship_id", $championship_id);
            //->where('championships.status_id', 2);

            if ($schedule) {
                $query->whereDate("schedule", $schedule);
            }
            $query->orderBy('schedule');
            return $paginate ? $query->paginate(10) : $query->get();
        } catch (QueryException $e) {
            Log::channel('database_errors')->error(__FUNCTION__ . ' - Erro ao buscar o game no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
    }

    public function findGamesByStatus($championship_id, $status)
    {
        try {
            return Game::where('status_id', $status)->where("championship_id", $championship_id)->get();
        } catch (QueryException $e) {
            Log::channel('database_errors')->error(__FUNCTION__ . ' - Erro ao buscar o game no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
    }

    public function findGamesByChampionship(Request $request, $championship_id, $paginate = false)
    {
        try {
            $search = $request->input('search');
            $query = Game::with(['teamOne', 'teamTwo', 'status', 'championship', 'court', 'setResults'])
                ->where("championship_id", $championship_id)
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->whereHas('teamOne', function ($teamQuery) use ($search) {
                            $teamQuery->where('player_one', 'ILIKE', "%$search%")
                                ->orWhere('player_two', 'ILIKE', "%$search%");
                        })->orWhereHas('teamTwo', function ($teamQuery) use ($search) {
                            $teamQuery->where('player_one', 'ILIKE', "%$search%")
                                ->orWhere('player_two', 'ILIKE', "%$search%");
                        });
                    });
                });

            $query->orderBy('schedule');
            return $paginate ? $query->paginate(10) : $query->get();
        } catch (QueryException $e) {
            Log::channel('database_errors')->error(__FUNCTION__ . ' - Erro ao buscar o game no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
    }

    public function findAllGamesByTeam(string $team_id)
    {
        try {
            return Game::with(['teamOne', 'teamTwo', 'status', 'championship', 'court', 'setResults'])
                ->where('team_one', $team_id)->orWhere('team_two', $team_id)
                ->orderBy('schedule')->get();
        } catch (QueryException $e) {
            Log::channel('database_errors')->error(__FUNCTION__ . ' - Erro ao buscar o game no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
    }

    public function update(string $id, $data)
    {
        try {
            $game = Game::findOrFail($id);
            return $game->update($data);
        } catch (QueryException $e) {
            Log::error('Erro ao atualizar jogo: ' . $e->getMessage());
            Log::channel('database_errors')->error(__FUNCTION__ . ' - Erro ao atualizar o game no banco de dados', [
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
            return Game::create($data);
        } catch (QueryException $e) {
            Log::error('Erro ao criar jogo: ' . $e->getMessage());
            Log::channel('database_errors')->error(__FUNCTION__ . ' - Erro ao criar o game no banco de dados', [
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
            $game = Game::find($id);
            if (!$game) {
                return collect();
            }
            return $game->delete();
        } catch (QueryException $e) {
            Log::error(__FUNCTION__ . ' - Erro ao excluir o jogo no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
    }
}
