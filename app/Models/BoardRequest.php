<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoardRequest extends Model
{
    protected $guarded = [];

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';

    const STATUS_VALUES = [
        self::STATUS_PENDING,
        self::STATUS_COMPLETED,
        self::STATUS_REJECTED,
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invite()
    {
        return $this->hasOne(Invite::class);
    }
}
