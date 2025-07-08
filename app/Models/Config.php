<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image'];

    public function courts()
    {
        return $this->hasMany(Court::class, 'company_id');
    }
}
