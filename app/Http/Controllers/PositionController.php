<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Position;

class PositionController extends Controller
{
    //return data table positions
    public function get()
    {
        return Position::get();
    }
}
