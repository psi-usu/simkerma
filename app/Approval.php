<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Approval extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'cooperation_id',
        'note',
        'created_by',
        'updated_by',
    ];

    public function cooperation()
    {
        return $this->belongsTo(Cooperation::class);
    }
}
