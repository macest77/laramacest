<?php

namespace App\Services;

use App\Services\StandingsService;
use App\Models\Standings;

class ApiStandingsService extends StandingsService
{
    public function getFullLastStanding()
    {
        if ( parent::getLastStanding() ) {
            if ( $standing = parent::getStanding($this->last_standing['id']))
                return $standing;
        }

        return false;
    }
}