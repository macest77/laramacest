<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiLottoService;

class ApiLottoController extends Controller
{

    private $_service;

    public function __construct(ApiLottoService $service)
    {
        $this->_service = $service;
    }

    public function getlastdraw()
    {
        $last_draw = $this->_service->getLastDraw();
        
        return response()->json($last_draw, 200);
    }

    public function getdraw($date)
    {
        $date = date("Y-m-d", strtotime($date));
    }
}
