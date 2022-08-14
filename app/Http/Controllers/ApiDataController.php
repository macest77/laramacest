<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\ApiMusicService;

class ApiDataController extends Controller
{
    private $_service;

    public function __construct(ApiMusicService $service)
    {
        $this->_service = $service;
    }

    public function getBandData($id)
    {
        $id = (int)$id;

        $band_data = $this->_service->getBandData($id);

        if (empty($band_data))
            return response()->json(array(), Response::HTTP_NOT_FOUND);
        else
            return response()->json($band_data, Response::HTTP_OK);
    }
}
