<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
	protected $table = 't_leads';
	protected $primaryKey = 'lead_pk_no';

	public function lead_life_cycle()
	{
		return $this->hasOne(LeadLifeCycle::class, 'lead_pk_no');
	}
}
