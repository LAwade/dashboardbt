<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['team_one', 'team_two', 'round', 'status_id', 'championship_id', 'category', 'court_id', 'schedule', 'date_start', 'date_end'];

    protected $casts = [
        'schedule' => 'datetime',
    ];

    public function teamOne()
    {
        return $this->belongsTo(Team::class, 'team_one');
    }

    public function teamTwo()
    {
        return $this->belongsTo(Team::class, 'team_two');
    }

    public function championship()
    {
        return $this->belongsTo(Championship::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class, 'court_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function setResults()
    {
        return $this->hasMany(SetResult::class, 'game_id');
    }
}
