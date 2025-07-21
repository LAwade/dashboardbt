<?php

namespace App\Http\Controllers;

use App\Events\StatusUpdated;
use App\Events\UpdatedEvent;
use App\Http\Services\CourtService;
use App\Http\Services\GameService;
use App\Http\Services\StatusService;
use App\Http\Services\TeamService;
use App\Models\Status;
use App\Models\Team;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class GameController extends Controller
{
    public function __construct(
        protected GameService $gameService,
        protected TeamService $teamService,
        protected CourtService $courtService,
        protected StatusService $statusService
    ) {}

    public function index(Request $request, string $championshipId)
    {
        $games = $this->gameService->findGamesByChampionship($request, $championshipId, true);
        return Inertia::render('Games/Index', [
            'games' => $games,
            'teams' => $this->teamService->findAllTeamByChampionship($championshipId),
            'courts' => $this->courtService->findAll(),
            'status' => $this->statusService->findAll(),
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->only(
                [
                    'schedule',
                    'championship_id',
                    'court_id',
                    'round',
                    'category',
                    'status_id',
                    'extend_id',
                    'date_start',
                    'date_end',
                    'team_one',
                    'team_two'
                ]
            );

            if ($data['team_one'] == $data['team_two']) {
                return redirect()->back()->with('error', 'Não foi possível criar o jogo. Os times não podem ser iguais.');
            }

            if ($data['status_id'] == 2) {
                $data['date_start'] = date('Y-m-d H:i:s');
                $data['date_end'] = null;
            }

            $this->gameService->create($data);
            broadcast(new UpdatedEvent);
            return redirect()->back()->with('success', 'Jogo criado com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao criar jogo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Não foi possível criar o jogo.');
        }
    }

    public function update(Request $request, $id)
    {

        try {
            $data = $request->only(
                [
                    'schedule',
                    'championship_id',
                    'court_id',
                    'round',
                    'category',
                    'status_id',
                    'extend_id',
                    'date_start',
                    'date_end'
                ]
            );

            $game = $this->gameService->findById($id);

            if ($game->status_id != $data['status_id'] && $data['status_id'] == 2) {
                $data['date_start'] = date('Y-m-d H:i:s');
                $data['date_end'] = null;
            }

            if ($game->status_id != $data['status_id'] && $data['status_id'] == 5) {
                $data['date_end'] = date('Y-m-d H:i:s');
            }

            $this->gameService->update($id, $data);

            $game = $this->gameService->findById($id);

            broadcast(new UpdatedEvent);
            return Inertia::location(url()->previous());
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar jogo: ' . $e->getMessage());
            return back(303)->with('error', 'Não foi possível atualizar o jogo.');
        }
    }

    public function status(Request $request, $id, $status)
    {
        $message = '';

        try {
            if ($status == 2) {
                $court = $this->courtService->findCourtByGameId($id);
                if (!$court) {
                    return redirect()->back()->with('error', 'Jogo inválido para esta quadra.');
                }

                $gameStatus = $this->gameService->findGameByCourtStatus($court->id, 2);
                if (count($gameStatus)) {
                    return redirect()->back()->with('error', 'Já existe outro jogo em andamento!');
                }
            }

            if ($status != 2 && $status != 5) {
                return redirect()->back()->with('error', 'Status inválido para este o jogo.');
            }

            $data = ['status_id' => $status];
            if ($status == 2) {
                $data['date_start'] = now();
                $message = 'Jogo iniciado com sucesso!';
            } elseif ($status == 5) {
                $data['date_end'] = now();
                $message = 'Jogo finalizado com sucesso!';
            }

            if (!$request->user()->isJudgeGeneral() && !$request->user()->isAdmin()) {
                return redirect()->back()->with('error', 'Você não tem permissão para gerenciar este jogo.');
            }

            $game = $this->gameService->update($id, $data);
            if (!$game) {
                throw new Exception('Não foi possível atualizar o registro!');
            }

            $game = $this->gameService->findById($id);
            broadcast(new StatusUpdated($game));
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            Log::channel('exception')->error('Erro ao atualizar o status do jogo', [
                'exception' => $e->getMessage(),
                'input' => $request->all(),
            ]);
        }

        return redirect()->back()->with('error', 'Houve um erro ao atualizar o status do jogo. Por favor, tente novamente mais tarde.');
    }

    public function destroy(string $id)
    {
        try {
            $game = $this->gameService->findById($id);
            $this->gameService->delete($id);
            broadcast(new UpdatedEvent);
            return Inertia::location(route('games.index', ['id' => $game->championship_id, 'page' => 1]));
        } catch (\Exception $e) {
            Log::channel('exception')->error('Erro ao excluir o jogo', [
                'exception' => $e->getMessage()
            ]);
        }
    }
}
