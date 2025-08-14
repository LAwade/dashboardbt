<?php

namespace App\Events;

use App\Http\Resources\GameResource;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Support\Facades\Log;

class StatusUpdated implements ShouldBroadcastNow
{
    /**
     * BROADCAST PARA ATUALIZAÇÃO DA TELA DE JUIZES
     *  --> IMPORTANTE PARA QUE ARBITROS NÃO INICIE JOGOS OU ALTERE, NO QUAL, JÁ FORAM 
     * ATUALIZADO POR OUTROS JUIZ.
     */
    public $game;

    public function __construct($game)
    {
        $this->game = $game;
    }

    public function broadcastOn()
    {
        return [new Channel('games')];
    }

    public function broadcastWith()
    {
        return (new GameResource($this->game))->resolve();
    }

    public function broadcastAs()
    {
        return 'status.updated';
    }
}
