<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\TeamService;
use Illuminate\Http\Request;

class TeamAPIController extends Controller
{

    public function __construct(
        protected TeamService $teamService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function findByName(string $name)
    {
        $players = $this->teamService->findByName($name);

        if (count($players) == 0) {
            return response()->json(['status' => false, "message" => "Nenhum jogador encontrado com essas informações!"], 404);
        }

        return response()->json(['status' => false, "message" => "Jogadores encontrados!", "data" => $players], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $players = $this->teamService->findById($id);

        if (count($players) == 0) {
            return response()->json(['status' => false, "message" => "Nenhum jogador encontrado com essas informações!"]);
        }

        return response()->json(['status' => false, "message" => "Jogadores encontrados!", "data" => $players]);
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
