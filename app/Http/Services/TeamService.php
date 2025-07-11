<?php

namespace App\Http\Services;

use App\Models\Court;
use App\Models\Game;
use App\Models\Team;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeamService
{
    public function findByName(string $name)
    {
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
            return collect();
        }
    }

    public function findById(string $id)
    {
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
            return collect();
        }
    }

    public function findTeamByChampionship(Request $request, $championshipId, int $limit = 10)
    {
        try {
            $search = $request->input('search');

            $teams = Team::where(function ($query) use ($championshipId) {
                $query->whereHas('matchesAsTeamOne', function ($q) use ($championshipId) {
                    $q->where('championship_id', $championshipId);
                })->orWhereHas('matchesAsTeamTwo', function ($q) use ($championshipId) {
                    $q->where('championship_id', $championshipId);
                });
            })
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('player_one', 'ILIKE', "%$search%")  // PostgreSQL (ILIKE é case-insensitive)
                            ->orWhere('player_two', 'ILIKE', "%$search%");
                    });
                })
                ->orderBy('player_one')
                ->paginate(10);
            return $teams;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao buscar jogador no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        } catch (\Exception $e) {
            return collect();
        }
    }

    public function findTeamByNameAndChampionship(string $name, string $championshipId)
    {
        try {
            $teams = Team::whereHas('matchesAsTeamOne', function ($query) use ($championshipId) {
                $query->where('championship_id', $championshipId);
            })->orWhereHas('matchesAsTeamTwo', function ($query) use ($championshipId) {
                $query->where('championship_id', $championshipId);
            })->distinct()
                ->where('player_one', 'ILIKE', "%{$name}%")
                ->orWhere('player_two', 'ILIKE', "%{$name}%")->get();
            return $teams;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao buscar jogador no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        } catch (\Exception $e) {
            return collect();
        }
    }
}
