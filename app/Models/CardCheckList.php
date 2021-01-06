<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CardCheckList extends Model
{
    use SoftDeletes;

    const STATUS_COMPLETE = 'complete';
    const STATUS_INCOMPLETE = 'incomplete';

    const STATUS_VALUES = [
        self::STATUS_COMPLETE,
        self::STATUS_INCOMPLETE,
    ];

    protected $guarded = [];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
