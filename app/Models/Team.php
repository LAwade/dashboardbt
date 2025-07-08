<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['player_one', 'player_two'];

    public function matchesAsTeamOne()
    {
        return $this->hasMany(Game::class, 'team_one');
    }

    public function matchesAsTeamTwo()
    {
        return $this->hasMany(Game::class, 'team_two');
    }
}
