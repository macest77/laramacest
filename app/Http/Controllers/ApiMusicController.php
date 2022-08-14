<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\ApiStandingsService;

class ApiMusicController extends Controller
{

    private $_service;

    public function __construct(ApiStandingsService $service)
    {
        $this->_service = $service;
    }

    public function getlaststanding()
    {
        $this->_service->getLastStanding();
        
        if ($last_standing = $this->_service->getFullLastStanding() )
            return response()->json($last_standing, Response::HTTP_OK);
        else
            return response()->json(array(), Response::HTTP_NOT_FOUND);
    }

    public function getstanding($id)
    {
        $id = (int)$id;

    }

}

