<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes;

    public function users()
    {
        return $this->belongsToMany(User::class, 'team_users');
    }

    public function boards()
    {
        return $this->belongsToMany(Board::class, 'board_teams');
    }
}
