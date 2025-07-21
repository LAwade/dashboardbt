<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;
    
    public $incrementing = false;
    protected $keyType = 'string';   
    protected $fillable = ['id','name', 'number', 'enable'];

}
