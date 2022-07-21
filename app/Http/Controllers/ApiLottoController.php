<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiLottoController extends Controller
{
    public function getlastdraw()
    {

    }

    public function getdraw($date)
    {
        $date = date("Y-m-d", strtotime($date));
    }
}
