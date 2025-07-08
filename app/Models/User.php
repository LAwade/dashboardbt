<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use HasFactory, Notifiable, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'permission_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    public function hasPermission(int $permission_id): bool
    {
        return $this->permission?->id === $permission_id;
    }

    public function isAdmin(): bool
    {
        return $this->hasPermission(Permission::ADMIN);
    }

    public function isJudgeGeneral(): bool
    {
        return $this->hasPermission(Permission::JUDGE_GENERAL);
    }

    public function isJudgeCourt(): bool
    {
        return $this->hasPermission(Permission::JUDGE_COURT);
    }
}
