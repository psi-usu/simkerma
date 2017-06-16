<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAuth extends Model
{
    protected $fillable = [
        'auth_type',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

    public function auth()
    {
        return $this->belongsTo(Auth::class, 'auth_type', 'type');
    }
}
