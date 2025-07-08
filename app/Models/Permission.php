<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    const ADMIN = '1';
    const JUDGE_GENERAL = '2';
    const JUDGE_COURT = '3';
    const PLAYER = '4';

    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

}
