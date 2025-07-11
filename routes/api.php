<?php

use App\Http\Controllers\Api\PanelAPIController;
use App\Http\Controllers\Api\TeamAPIController;
use App\Http\Controllers\UserController;
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


// Route::get('/courts', function (Request $request) {
//     $service = new CourtService;
//     $courts = $service->findAll();

//     if ($courts) {
//         return response()->json(['status' => true, 'data' => $courts], 200);
//     } else {
//         return response()->json(['status' => false, 'message' => 'Não foi possível buscar os dados'], 404);
//     }
// });

Route::prefix('data')->group(function(){
    Route::get('/panel/{championship}', [PanelAPIController::class, 'findGamesByChampionship']);
    Route::get('/court/{championship}', [PanelAPIController::class, 'findGamesByCourt']);
});

Route::get('/team/{name}', [TeamAPIController::class, 'findByName']);

Route::fallback(function () {
    return response()->json([
        'message' => 'Rota não encontrada. Verifique a documentação da API.'
    ], 404);
});