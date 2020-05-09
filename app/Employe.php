<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    public function position()
    {
        //return position for employe
        return $this->belongsTo('App\Position');
    }

    public function civilstate()
    {
        //return position for employe
        return $this->belongsTo('App\CivilState', 'civilstate_id');
    }

    public function immediateBoss()
    {
        //return position for employe
        return $this->belongsTo('App\Employe', 'immediateboss_id')->with('position');
    }
}
