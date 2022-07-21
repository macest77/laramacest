<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiMusicController extends Controller
{
    public function getlaststanding()
    {

    }

    public function getstanding($id)
    {
        $id = (int)$id;
    }
}
