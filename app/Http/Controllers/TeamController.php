<?php

namespace App\Http\Controllers;

use App\Http\Services\ChampionshipService;
use App\Http\Services\TeamService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function __construct(
        protected TeamService $teamService,
        protected ChampionshipService $championshipService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $championshipId)
    {
        $championship = $this->championshipService->findById($championshipId);
        $teams = $this->teamService->findTeamByChampionship($request, $championshipId, 10);
        return Inertia::render('Team/Index', [
            'teams' => $teams,
            'championship' => $championship
        ]);
    }

    public function findTeamByNameAndChampionship($name, $championshipId)
    {
        $teams = $this->teamService->findTeamByNameAndChampionship($name, $championshipId);
        return Inertia::render('Team/Index', [
            'teams' => $teams
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $data = $request->validate([
            'player_one' => 'required|string|max:255',
            'player_two' => 'required|string|max:255',
            'championship_id' => 'required|exists:championships,id',
        ]);



        $this->teamService->create($data);

        return Inertia::location(route('team.index', ['championshipId' => $data['championship_id'], 'page' => 1]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'player_one' => 'required|string|max:255',
            'player_two' => 'required|string|max:255',
            'championship_id' => 'required|exists:championships,id',
        ]);

        $this->teamService->update($id, $data);

        return Inertia::location(route('team.index', ['championshipId' => $data['championship_id'], 'page' => 1]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $team = $this->teamService->findById($id);
        $this->teamService->delete($id);
        return Inertia::location(route('team.index', ['championshipId' => $team->championship_id, 'page' => 1]));
    }
}
