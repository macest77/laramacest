<?php

namespace App\Services;

use App\Services\LottoService;
use App\Models\Lotto;

class ApiLottoService extends LottoService
{
    public function getLastDraw()
    {
        return Lotto::orderBy('draw_date', 'desc')->first();
    }
}