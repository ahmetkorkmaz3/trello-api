<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'first_name', 'last_name', 'username'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function boards()
    {
        return $this->belongsToMany(Board::class, 'board_users');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_users');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function boardRequests()
    {
        return $this->hasMany(BoardRequest::class);
    }

    public function teamRequests()
    {
        return $this->hasMany(TeamRequest::class);
    }
}
