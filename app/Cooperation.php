<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cooperation extends Model {
    use SoftDeletes;
    protected $fillable = [
        'partner_id',
        'cooperation_id',
        'coop_type',
        'is_accidental',
        'accidental_id',
        'subject_of_coop',
        'area_of_coop',
        'sign_date',
        'end_date',
        'form_of_coop',
        'usu_doc_no',
        'partner_doc_no',
        'file_name_ori',
        'file_name',
        'implementation',
        'unit',
        'contract_amount',
        'status',
        'created_by',
        'updated_by',
    ];

    public function coopItem()
    {
        return $this->hasMany(CoopItem::class);
    }

    public function approval()
    {
        return $this->hasMany(Approval::class);
    }

    public function coopType()
    {
        return $this->hasOne(CoopType::class, 'type', 'coop_type');
    }

    public function statusCode()
    {
        return $this->hasOne(StatusCode::class, 'code', 'status');
    }

    public function accidental()
    {
        return $this->hasOne(Accidental::class, 'id', 'accidental_id');
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function areaCoop()
    {
        return $this->hasOne(AreasCoop::class, 'id', 'area_of_coop');
    }
}