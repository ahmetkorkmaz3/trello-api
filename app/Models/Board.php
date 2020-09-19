<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Board extends Model
{
    use SoftDeletes;

    public function users()
    {
        return $this->belongsToMany(User::class, 'board_user');
    }

    public function columns()
    {
        return $this->hasMany(Column::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'board_team');
    }
}
