<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\CourtService;
use App\Http\Services\GameService;
use App\Http\Services\StatusService;
use Illuminate\Support\Facades\Log;

class PanelAPIController extends Controller
{
    public function __construct(
        protected GameService $gameService,
        protected CourtService $courtService,
        protected StatusService $statusService
    ) {}

    public function findGamesByChampionship(string $id)
    {
        try {
            $games = $this->gameService->findAllGamesBySchedule($id);
            $gamesFinished = $this->gameService->findGamesByStatus($id, 5);
            $gamesWaiting = $this->gameService->findGamesByStatus($id, 1);
            $gamesJoin = $this->gameService->findGamesByStatus($id, 2);

            return response()->json(['status' => true, 'data' => ["games" => $games ?? [], "finished" => count($gamesFinished), "waiting" => count($gamesWaiting), "in_progress" => count($gamesJoin)]], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching games by championship: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Não foi possível encontrar os dados!'], 404);
        }
    }

    public function findGamesByCourt(string $id)
    {
        try {
            $games = $this->gameService->findWithTeamByCourt($id);
            $court = $this->courtService->findById($id);
            $firstGame = $this->gameService->findFirstGameByCourt($id);
            $courts = $this->courtService->findAll();
            $status = $this->statusService->findAll();

            return response()->json([
                'status' => true,
                'data' => [
                    "games" => $games ?? [],
                    "court" => $court,
                    "first_game" => $firstGame,
                    "courts" => $courts,
                    "status" => $status
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching games by court: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Não foi possível encontrar os dados!'], 404);
        }
    }
}
