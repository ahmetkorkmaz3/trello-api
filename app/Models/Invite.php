<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Illuminate\Support\Str;

/**
 * Class Invite
 * @package App\Models
 */
class Invite extends Model
{
    use SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';

    const TYPE_TEAM = 'team';
    const TYPE_BOARD = 'board';

    const STATUS_VALUES = [
        self::STATUS_PENDING,
        self::STATUS_COMPLETED,
        self::STATUS_REJECTED,
    ];

    const TYPE_VALUES = [
        self::TYPE_BOARD,
        self::TYPE_TEAM,
    ];

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($invite) {
            $invite->token = Str::random(60);
        });
    }

    public function getSharedUrlAttribute()
    {
        return env('APP_URL') . '/register/' . $this->token;
    }

    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    public function complete()
    {
        return $this->update(['status' => self::STATUS_COMPLETED]);
    }
}
