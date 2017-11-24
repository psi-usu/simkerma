<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AreasCoop extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'area_coop',
        'created_by',
        'updated_by',
    ];
}
