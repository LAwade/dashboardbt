<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class UpdatedEvent implements ShouldBroadcastNow
{
    public function broadcastOn()
    {
        return [new Channel('games')];
    }

    public function broadcastWith()
    {
        return ['message' => 'data is updated', 'status' => true];
    }

    public function broadcastAs()
    {
        return 'updated.event';
    }
}
