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
    public function topViews(){
        return $this->hasOne('App\View')
                ->orderBy('view_count' ,'desc');
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($disease) { // before delete() method call this
                $disease->solutions()->delete();
                $disease->view()->delete();
        });
    }
}
