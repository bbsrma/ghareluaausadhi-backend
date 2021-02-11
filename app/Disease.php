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

        // this is a recommended way to declare event handlers
        public static function boot() {
            parent::boot();
    
            static::deleting(function($disease) { // before delete() method call this
                 $disease->solutions()->delete();
                 $disease->view()->delete();
                 // do the rest of the cleanup...
            });
        }
}
