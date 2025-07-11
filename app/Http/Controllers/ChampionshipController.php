<?php

namespace App\Http\Controllers;

use App\Http\Services\ChampionshipService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChampionshipController extends Controller
{
    public function __construct(
        protected ChampionshipService $championshipService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $championships = $this->championshipService->findAll();
        return Inertia::render('Championships/Index', [
            'championships' => $championships
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
    public function show(string $name)
    {
        $championships = $this->championshipService->findByName($name);
        $message = '';

        if(count($championships) === 0) {
            $message = 'Nenhum campeonato encontrado com o nome: ' . $name;
            $championships = $this->championshipService->findAll();
        }

        return Inertia::render('Championships/Index', [
            'championships' => $championships,
            'message' => $message
        ]);
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
