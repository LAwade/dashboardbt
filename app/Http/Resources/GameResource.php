<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status_id' => $this->status_id,
            'status' => $this->status,

            'court_id' => $this->court_id,
            'court' => $this->court,

            'championship_id' => $this->championship_id,
            'championship' => new $this->championship,

            'category' => $this->category,
            'round' => $this->round,
            'schedule' => $this->schedule,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,

            'team_one' => $this->teamOne,
            'team_two' => $this->teamTwo,

            'set_results' => $this->setResults,

            'extend_id' => $this->extend_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
