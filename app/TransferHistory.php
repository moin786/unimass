<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferHistory extends Model
{
    protected $table = 't_leadtransferhistory';
    protected $primaryKey = 'transhistory_pk_no';
}
