<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    //
    public function disease(){
        return $this->belongsTo('App\Disease', 'disease_id','id');
    }
}
