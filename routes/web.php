<?php

use App\Http\Controllers\ChampionshipController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SetResultController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PanelController::class, 'index'])->name('home');
Route::get('/panel/{championship}', [PanelController::class, 'games'])->name('panel.championship');
Route::post('/panel/findplayer', [PanelController::class, 'findPlayer'])->name('panel.findplayer');
Route::get('/panel/player/{id}', [PanelController::class, 'player'])->name('panel.player');
//Route::get('/login', [AuthenticatedSessionController::class, 'create']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /** GERENCIAR QUADRAS */
    Route::get('/courts', [CourtController::class, 'index'])->name('courts.index');
    Route::post('/courts', [CourtController::class, 'store'])->name('courts.store');
    Route::put('/courts/{court}', [CourtController::class, 'update'])->name('courts.update');
    Route::delete('/courts/{court}', [CourtController::class, 'destroy'])->name('courts.destroy');

    /** GERENCIAR JOGOS */
    Route::get('/games/edit/{id}', [GameController::class, 'edit'])->name('games.edit');
    Route::get('/games/status/{id}/{status}', [GameController::class, 'status'])->name('games.status');
    Route::post('/games', [GameController::class, 'store'])->name('games.store');
    Route::put('/games/{id}', [GameController::class, 'update'])->name('games.update');
    Route::get('/games/championship/{id}', [GameController::class, 'index'])->name('games.index');
    Route::delete('/games/{id}', [GameController::class, 'destroy'])->name('games.destroy');

    /** GERENCIAR RESULTADOS */
    Route::post('/result/{id}', [SetResultController::class, 'create'])->name('result.create');

    /** GERENCIAR JOGADORES */
    Route::get('/team/{championshipId}', [TeamController::class, 'index'])->name('team.index');
    Route::post('/team/{name}/{championshipId}', [TeamController::class, 'findTeamByNameAndChampionship'])->name('team.find');
    Route::post('/team', [TeamController::class, 'store'])->name('team.store');
    Route::delete('/team/{id}', [TeamController::class, 'destroy'])->name('team.destroy');

    /** GERENCIAR CAMPEONATOS */
    Route::get('/championships', [ChampionshipController::class, 'index'])->name('championships.index');
    Route::post('/championships', [ChampionshipController::class, 'store'])->name('championships.store');
    Route::put('/championships/{id}', [ChampionshipController::class, 'update'])->name('championships.update');
    Route::delete('/championships/{id}', [ChampionshipController::class, 'destroy'])->name('championships.destroy');

    /** GERENCIAR PESSOAS */
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::fallback(function () {
    return redirect()->route('home');
});

require __DIR__ . '/auth.php';
