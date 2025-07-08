<?php

namespace App\Http\Services;

use App\Models\Game;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class GameService
{

    public function findById(string $id){
        return Game::find($id);
    }

    public function findWithTeamByCourt(string $id)
    {
        try {
            $games = Game::with(['teamOne', 'teamTwo', 'status', 'setResults'])
                ->where('court_id', $id)
                ->orderBy('schedule', 'asc')
                ->get();

            return $games;
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
            Log::channel('database_errors')->error('Erro ao adicionar a cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function findGameByCourtStatus($court, $status){
        try {
            return Game::where('court_id', $court)->where('status_id', $status)->get();
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
    }

    public function findAllGamesBySchedule($championship_id, $schedule = '2025-08-28'){
        try {
            return Game::with(['teamOne', 'teamTwo', 'status', 'championship', 'court','setResults'])
            ->where("championship_id", $championship_id)
            ->orderBy('schedule')->get();
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
    }

    public function findGamesByStatus($championship_id, $status){
        try {
            return Game::where('status_id', $status)->where("championship_id", $championship_id)->get();
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
    }

    public function findGamesByChampionship(){
        try {
            return Game::with(['championship'])->get();
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
    }

    public function findAllGamesByTeam(string $team_id){
        try {
            return Game::with(['teamOne', 'teamTwo', 'status', 'championship', 'court','setResults'])
                ->where('team_one', $team_id)->orWhere('team_two', $team_id)
                ->orderBy('schedule')->get();
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
    }

    public function update(string $id, $data)
    {
        try {
            $game = Game::findOrFail($id);
            return $game->update($data);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao adicionar a cidade no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return false;
    }
}
