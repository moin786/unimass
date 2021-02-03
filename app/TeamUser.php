<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamUser extends Model
{
    protected $table = 's_user';
    protected $primaryKey = 'user_pk_no';

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function team()
    {
        return $this->hasOne('App\TeamAssign','user_pk_no');
    }

}
