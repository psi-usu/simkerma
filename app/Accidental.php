<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accidental extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'partner_id',
        'form_of_coop',
        'reason',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
