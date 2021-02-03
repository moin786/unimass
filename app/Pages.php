<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    protected $table = 's_pages';
    protected $primaryKey = 'page_pk_no';

    public function rbac()
    {
        return $this->hasMany('App\Rback','page_pk_no');
    }

}
