<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    //

    protected $table = 'profiles';
    protected $primaryKey = 'id';

    protected $fillable=['country_id','city_id','address','gender','image','phone','dob','user_id'];

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function country(){
        return $this->hasOne('App\Country');
    }
    public function city(){
        return $this->hasOne('App\City');
    }
}
