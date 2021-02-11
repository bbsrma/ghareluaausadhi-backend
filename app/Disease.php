<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    //
    public function solutions(){
        return $this->hasMany('App\Solution');
    }
    public function user(){
        return $this->belongsTo('App\User', 'postedby');
    }
    public function view(){
        return $this->hasOne('App\View');
    }
}
