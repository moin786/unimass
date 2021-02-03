<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadTransfer extends Model
{
    protected $table = 't_leadtransfer';
	protected $primaryKey = 'transfer_pk_no';
	protected $fillable=['transfer_pk_no','lead_pk_no'];
}
