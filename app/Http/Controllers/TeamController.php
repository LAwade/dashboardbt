<?php

namespace App\Http\Controllers;

use App\Http\Services\TeamService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function __construct(
        protected TeamService $teamService
    ) { }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $championshipId)
    {
        $teams = $this->teamService->findTeamByChampionship($request, $championshipId, 10);
        return Inertia::render('Team/Index', [
            'teams' => $teams
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }
}
