<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, HasMedia
{
    use Notifiable, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name', 'email', 'password', 'first_name', 'last_name', 'username'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function boards(): BelongsToMany
    {
        return $this->belongsToMany(Board::class, 'board_users');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_users');
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function teamInvites(): HasMany
    {
        return $this->hasMany(TeamUserInvite::class)
            ->where('status', TeamUserInvite::STATUS_PENDING);
    }

    public function boardInvites(): HasMany
    {
        return $this->hasMany(BoardUserInvite::class)
            ->where('status', BoardUserInvite::STATUS_PENDING);
    }
}
