<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LookupData extends Model
{
    protected $table = 's_lookdata';
    protected $primaryKey = 'lookup_pk_no';
    protected $fillable = [
        'lookup_pk_no', 'lookup_type', 'lookup_name',
    ];

    public function user()
    {
        return $this->hasMany('App\User','lookup_pk_no');
    }
}
