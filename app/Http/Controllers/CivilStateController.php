<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CivilState;

class CivilStateController extends Controller
{
    //return data table civilstates
    public function get()
    {
        return CivilState::get();
    }
}
