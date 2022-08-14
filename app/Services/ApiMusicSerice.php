<?php

namespace App\Services;

use App\Models\Bands;

class ApiMusicSerice
{
    public function getBandData(int $id)
    {
        return Bands::where('band_id', $id);
    }
}