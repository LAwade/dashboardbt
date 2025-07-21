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

    public function __construct(
        protected CourtService $courtService,
        protected GameService $gameService,
        protected StatusService $statusService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $name = $request->input('search');
        $courts = $this->courtService->findAll();
        $findCourts = [];
        if ($name) {
            $findCourts = $this->courtService->findByName($name);
        }

        if (count($findCourts)) {
            $courts = $findCourts;
        }

        return Inertia::render('Courts/Index', [
            'courts' => $courts
        ]);
    }

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
            'enable' => 'required|boolean',
        ]);
        $response = $this->courtService->create($data);
        if (!$response) {
            return redirect()->route('courts.index')->with('error', 'Erro ao salvar a quadra. Tente novamente.');
        }
        return redirect()->route('courts.index')->with('success', 'Quadra salva com sucesso!');
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

        return Inertia::render('Courts/PainelGeral', [
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
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $flash = [];
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|integer',
            'enable' => 'required|boolean',
        ]);

        $response = false;
        if (isset($id)) {
            $response = $this->courtService->update($id, $data);
        }

        if (!$response) {
            $flash['error'] = 'Erro ao atualizar a quadra. Tente novamente!';
        } else {
            $flash['success'] = 'Quadra atualizada com sucesso!';
        }

        return Inertia::render('Courts/Index', [
            'courts' => $this->courtService->findAll(),
            'flash' => $flash
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = $this->courtService->delete($id);
        $flash = [];

        if (!$response) {
            $flash['error'] = 'Erro ao excluir a quadra. Tente novamente!';
        } else {
            $flash['success'] = 'Quadra excluído com sucesso!';
        }

        session()->flash('success', 'Quadra excluído com sucesso!');
        return Inertia::location(route('courts.index'));
    }
}
