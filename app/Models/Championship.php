<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Championship extends Model
{
    use HasFactory;

    protected $keyType = 'string';        // 👈 necessário se for UUID
    public $incrementing = false;

    protected $fillable = ['name', 'image', 'description', 'status_id', 'date'];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
