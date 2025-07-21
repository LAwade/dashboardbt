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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => 'nullable|exists:teams,id',
            'player_one' => 'required|string|max:255',
            'player_two' => 'required|string|max:255',
            'championship_id' => 'required|exists:championships,id',
        ]);

        if (isset($data['id'])) {
            $this->teamService->update($data['id'], $data);
        } else {
            $this->teamService->create($data);
        }

        return Inertia::location(route('team.index', ['championshipId' => $data['championship_id'], 'page' => 1]));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
