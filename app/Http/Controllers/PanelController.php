<?php

namespace App\Http\Controllers;

use App\Http\Services\ChampionshipService;
use App\Http\Services\CourtService;
use App\Http\Services\GameService;
use App\Http\Services\TeamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PanelController extends Controller
{
    public function __construct(
        protected CourtService $courtService,
        protected GameService $gameService,
        protected TeamService $teamService,
        protected ChampionshipService $championshipService
    ) {}
    
    public function index()
    {
        $championships = $this->championshipService->findChampionshipAndGames();
        return Inertia::render('Panel', ['championships' => $championships]);
    }

    public function games($championship_id)
    {
        $games = $this->gameService->findAllGamesBySchedule($championship_id);
        $gamesFinished = $this->gameService->findGamesByStatus($championship_id, 5);
        $gamesWaiting = $this->gameService->findGamesByStatus($championship_id, 1);
        $gamesJoin = $this->gameService->findGamesByStatus($championship_id, 2);
        return Inertia::render('Games', ["games" => $games, "finished" => $gamesFinished, "waiting" => $gamesWaiting, "join" => $gamesJoin]);
    }

    public function findPlayer(Request $request)
    {
        $data = $request->only(['name']);
        
        if(count($data) == 0){
            return redirect()->route('home')->withErrors(['error' => 'Erro ao carregar livros']);
        }

        $player = $this->teamService->findByName($data['name']);

        if ($data && count($player) == 0) {
            back()->with('error', 'Jogo inválido para esta quadra.');
            return Inertia::render('Panel');
        }

        if (count($player) > 1) {
            return Inertia::render('Panel', ['players' => $player]);
        }

        return redirect()->route('panel.player', ['id' => $player[0]->id]);
    }

    public function player(string $id)
    {
        $gamesTeam = $this->gameService->findAllGamesByTeam($id);
        return Inertia::render('Team/TeamGames', [
            'games' => $gamesTeam
        ]);
    }
}
