<?php

namespace App\Http\Controllers;

use App\Http\Services\ChampionshipService;
use App\Http\Services\CourtService;
use App\Http\Services\GameService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(
        protected CourtService $courtService,
        protected GameService $gameService,
        protected ChampionshipService $championshipService
    ) {}

    public function index()
    {
        $courts = $this->courtService->findAllWithGames();

        if ($courts === false) {
            return Inertia::render('Error', [
                'message' => 'Failed to retrieve courts data.'
            ]);
        }

        return Inertia::render('Dashboard', [
            'courts' => $courts
        ]);
    }

    public function games($championship_id)
    {
        $games = $this->gameService->findAllGamesBySchedule($championship_id);
        $gamesFinished = $this->gameService->findGamesByStatus($championship_id, 5);
        $gamesWaiting = $this->gameService->findGamesByStatus($championship_id, 1);
        $gamesJoin = $this->gameService->findGamesByStatus($championship_id, 2);
        return Inertia::render('Games', ["games" => $games, "finished" => $gamesFinished, "waiting" => $gamesWaiting, "join" => $gamesJoin]);
    }

    
}
