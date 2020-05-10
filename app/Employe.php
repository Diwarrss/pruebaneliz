<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
  //obtenemos el id con los 0 a su izquierda
    /* public function getIdAttribute($value)
    {
        if($value){
            return str_pad($value, 4, '0', STR_PAD_LEFT);
        }else{
            return null;
        }
    } */

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
