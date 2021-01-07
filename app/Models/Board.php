<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity;

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

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function userInvites(): HasMany
    {
        return $this->hasMany(BoardUserInvite::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'subject_id');
    }
}
