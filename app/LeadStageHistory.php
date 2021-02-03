<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadStageHistory extends Model
{
    protected $table = 't_leadstagehistory';
    protected $primaryKey = 'lead_stage_pk_no';
}
