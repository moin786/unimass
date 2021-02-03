<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rback extends Model
{
    protected $table = 's_rbac';
    protected $primaryKey = 'rbac_pk_no';

    public function pages()
    {
        return $this->hasMany('App\Pages', 'page_pk_no');
    }
}
