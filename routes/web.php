<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SetResultController;
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
    Route::get('/courts/{court}', [CourtController::class, 'show'])->name('courts.show');
    
    Route::get('/courts/create', [CourtController::class, 'create'])->name('courts.create');
    Route::get('/courts/{court}/edit', [CourtController::class, 'edit'])->name('courts.edit');
    Route::post('/courts', [CourtController::class, 'store'])->name('courts.store');
    Route::put('/courts/{court}', [CourtController::class, 'update'])->name('courts.update');
    Route::delete('/courts/{court}', [CourtController::class, 'destroy'])->name('courts.destroy');

    /** GERENCIAR JOGOS */

    Route::get('/games/edit/{id}', [GameController::class, 'edit'])->name('games.edit');
    Route::get('/games/status/{id}/{status}', [GameController::class, 'status'])->name('games.status');
    Route::put('/games/{id}', [GameController::class, 'update'])->name('games.update');

    Route::post('/result/{id}', [SetResultController::class, 'create'])->name('result.create');

    /** GERENCIAR JOGADORES */

    /** GERENCIAR PESSOAS */

    
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
