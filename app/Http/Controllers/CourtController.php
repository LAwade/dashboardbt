<?php

namespace App\Http\Controllers;

use App\Http\Services\CourtService;
use App\Http\Services\GameService;
use App\Http\Services\StatusService;
use App\Models\Court;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourtController extends Controller
{

    public function __construct(protected CourtService $courtService, protected GameService $gameService, protected StatusService $statusService) {}

    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Courts/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Logic to store a new court
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|integer',
            'company_id' => 'required|exists:companies,id',
        ]);
        // Assuming you have a Court model
        // Court::create($data);
        return redirect()->route('courts.index')->with('success', 'Court created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $games = $this->gameService->findWithTeamByCourt($id);
        $court = $this->courtService->findById($id);
        $firstGame = $this->gameService->findFirstGameByCourt($id);

        $courts = $this->courtService->findAll();
        $status = $this->statusService->findAll();

        return Inertia::render('Courts/Index', [
            'games' => $games,
            'court' => $court,
            'courts' => $courts,
            'status' => $status,
            'firstGame' => $firstGame,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
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
