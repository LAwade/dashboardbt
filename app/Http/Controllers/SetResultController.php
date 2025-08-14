<?php

namespace App\Http\Controllers;

use App\Events\StatusUpdated;
use App\Events\UpdatedEvent;
use App\Http\Services\GameService;
use App\Http\Services\SetResultService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Str;

class SetResultController extends Controller
{
    public function __construct(protected SetResultService $setResultService, protected GameService $gameService)
    {
        // Constructor logic if needed
    }

    public function create(Request $request)
    {
        $data = $request->only([
            'id',
            'team_one',
            'team_two',
            'game_team_one_1',
            'game_team_one_2',
            'game_team_one_tie',
            'game_team_two_1',
            'game_team_two_2',
            'game_team_two_tie',
            'walkover'
        ]);

        $setsCriados = 0;

        $rounds = [
            ['team1' => 'game_team_one_1', 'team2' => 'game_team_two_1', 'tie' => false],
            ['team1' => 'game_team_one_2', 'team2' => 'game_team_two_2', 'tie' => false],
            ['team1' => 'game_team_one_tie', 'team2' => 'game_team_two_tie', 'tie' => true],
        ];

        try {
            $resultGame = $this->setResultService->findByGame($data['id']); // deve ser uma Collection

            foreach ($rounds as $index => $round) {
                $t1 = $data[$round['team1']] ?? null;
                $t2 = $data[$round['team2']] ?? null;

                if (!is_null($t1) && !is_null($t2)) {
                    $setData = [
                        'game_id'   => $data['id'],
                        'team_win'  => $t1 > $t2 ? $data['team_one']['id'] : $data['team_two']['id'],
                        'game_win'  => max($t1, $t2),
                        'game_lose' => min($t1, $t2),
                        'wo'        => $data['walkover'] ?? false,
                        'tie_break' => $round['tie'],
                    ];

                    $existing = $resultGame[$index] ?? null;

                    $this->setResultService->updateOrCreate(
                        ['id' => $existing->id ?? Str::uuid()],
                        $setData
                    );

                    $setsCriados++;
                }

                if(isset($resultGame[$index]) && (!$t1 || !$t2)){
                    $this->setResultService->delete($resultGame[$index]->id);
                }
            }

            if ($setsCriados > 0) {
                $this->gameService->update($data['id'], ['status_id' => 5]);
            }

            $game = $this->gameService->findById($data['id']);
            broadcast(new StatusUpdated($game));

            broadcast(new UpdatedEvent);
            return Inertia::location(url()->previous());
        } catch (\Exception $e) {
            Log::error([
                'message' => 'Erro de validação',
                'errors' => $e->getMessage(),
            ]);
        }
    }
}
