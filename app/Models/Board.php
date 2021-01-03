<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Board extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'board_users');
    }

    public function columns(): HasMany
    {
        return $this->hasMany(Column::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'board_teams');
    }

    public function userInvites(): HasMany
    {
        return $this->hasMany(BoardUserInvite::class);
    }
}
