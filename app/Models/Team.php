<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'website',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_users');
    }

    public function boards(): HasMany
    {
        return $this->hasMany(Board::class);
    }

    public function teamUserInvites(): HasMany
    {
        return $this->hasMany(TeamUserInvite::class);
    }
}
