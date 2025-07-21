<?php

namespace App\Http\Controllers;

use App\Http\Services\ChampionshipService;
use App\Http\Services\StatusService;
use App\Models\Status;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChampionshipController extends Controller
{
    public function __construct(
        protected ChampionshipService $championshipService,
        protected StatusService $statusService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $findChampionship = [];
        $flash = [];
        $championships = $this->championshipService->findAll();
        $name = $request->input('search');

        if ($name) {
            $findChampionship = $this->championshipService->findByName($name);
        }

        if (count($findChampionship) > 0) {
            $championships = $findChampionship;
        }

        return Inertia::render('Championships/Index', [
            'championships' => $championships,
            'status' => $this->statusService->findAll(),
            'flash' => $flash
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'date' => 'required|date',
            'status_id' => 'required|exists:status,id',
        ]);

        $response = $this->championshipService->create($data);
        if (!$response) {
            return redirect()->route('championships.index')->with('error', 'Erro ao salvar o campeonato. Tente novamente.');
        }
        return redirect()->route('championships.index')->with('success', 'Campeonato salvo com sucesso!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $flash = [];
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'date' => 'required|date',
            'status_id' => 'required|exists:status,id',
        ]);

        $response = false;
        if (isset($id)) {
            $response = $this->championshipService->update($id, $data);
        }

        if (!$response) {
            $flash['error'] = 'Erro ao atualizar o campeonato. Tente novamente!';
        } else {
            $flash['success'] = 'Campeonato atualizado com sucesso!';
        }

        return Inertia::render('Championships/Index', [
            'championships' => $this->championshipService->findAll(),
            'status' => $this->statusService->findAll(),
            'flash' => $flash
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = $this->championshipService->delete($id);
        $flash = [];

        if (!$response) {
            $flash['error'] = 'Erro ao excluir o campeonato. Tente novamente!';
        } else {
            $flash['success'] = 'Campeonato excluído com sucesso!';
        }
        
        session()->flash('success', 'Campeonato excluído com sucesso!');
        return Inertia::location(route('championships.index'));
    }
}
