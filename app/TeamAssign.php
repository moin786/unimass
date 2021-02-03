<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamAssign extends Model
{
    protected $table = 't_teambuild';
    protected $primaryKey = 'teammem_pk_no';

    public function user()
    {
        return $this->hasOne('App\TeamUser','user_pk_no');
    }

    public function teamName()
    {
    	return $this->hasOne('App\LookupData','lookup_pk_no');
    }
}
