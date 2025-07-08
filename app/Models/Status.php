<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'status';
    protected $fillable = ['name', 'color'];

    public function championships()
    {
        return $this->hasMany(Championship::class);
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
