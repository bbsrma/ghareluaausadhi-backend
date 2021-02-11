<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    //
    public function disease(){
        return $this->belongsTO('App\Disease');
    }
}
