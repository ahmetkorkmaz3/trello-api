<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description',
    ];

    public function column()
    {
        return $this->belongsTo(Column::class);
    }
}
