<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Support\Facades\Log;

class StatusUpdated implements ShouldBroadcastNow
{
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
        Log::info('📡 Payload limpo do evento:', [
        'id' => $this->game->id,
        'status_id' => $this->game->status_id,
        'court_id' => $this->game->court_id,
        'category' => $this->game->category,
    ]);

        return [
            'id' => $this->game->id,
            'status_id' => $this->game->status_id
        ];
    }

    public function broadcastAs()
    {
        return 'status.updated';
    }
}
