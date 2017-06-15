<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoopType extends Model {
    use SoftDeletes;
    protected $fillable = [
        'type',
        'description',
        'created_by',
        'updated_by',
    ];
}
