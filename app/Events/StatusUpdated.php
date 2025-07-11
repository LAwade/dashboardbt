<?php

namespace App\Events;

use App\Http\Resources\GameResource;
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
        return (new GameResource($this->game))->resolve();
    }

    public function broadcastAs()
    {
        return 'status.updated';
    }
}
