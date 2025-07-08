<?php

use App\Events\StatusUpdated;
use App\Http\Controllers\Api\TeamAPIController;
use App\Http\Controllers\UserController;
use App\Http\Services\CourtService;
use App\Http\Services\GameService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('users', UserController::class);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/ping', function () {
        return response()->json([
            "message" => 'pong',
        ]);
    });
});

Route::get('/teste-evento', function () {
    broadcast(new StatusUpdated(\App\Models\Game::first()));
    return response()->json(['message' => 'Evento disparado'], 200);
});


Route::get('/courts', function (Request $request) {
    $service = new CourtService;
    $courts = $service->findAll();

    if ($courts) {
        return response()->json(['status' => true, 'data' => $courts], 200);
    } else {
        return response()->json(['status' => false, 'message' => 'Não foi possível buscar os dados'], 404);
    }
});


Route::get('/team/{name}', [TeamAPIController::class, 'findByName']);
