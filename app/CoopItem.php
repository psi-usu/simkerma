<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoopItem extends Model
{
    protected $fillable = [
        'item',
        'name',
        'quantity',
        'uom',
        'total_amount',
        'annotation',
    ];

    public function cooperation()
    {
        return $this->belongsTo(Cooperation::class);
    }
}
