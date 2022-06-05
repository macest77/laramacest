<?php

namespace App\Services;

use App\Models\Songi;
use App\Models\Songitemp;
use App\Models\Emailstext;
use DB;

class SongiService
{
    
    private $_code = '';
    
    public function getSongiList() : object
    {
        $songi_list = Songi::join('bands', 'bands.band_id', '=', 'songi_list.songi_list_band_id')
            ->where('songi_list_place', '>', 0)
            ->orderby('songi_list_place', 'asc')->get();
        
        return $songi_list;
    }
    
    public function createVoteCode(string $email) : string
    {
        $code1 = substr(md5(date('Y-m-d')),0,8);
        $code2 = md5($email);
        
        return $code1.$code2;
    }
    
    public function saveSongi(array $request) : bool
    {
        $insert = $request;
        
        $code= $this->createVoteCode($request['temp_songi_list_mail']);
        
        //echo '<br />'.$code;
        $insert['temp_songi_list_date'] = date("Y-m-d H:i:s");
        $insert['temp_songi_list_code'] = $code;
        $insert['temp_songi_list_mail'] = md5(md5($request['temp_songi_list_mail']));
        $standingsService = (new StandingsService);
        $next_standing = 1;
        if ($standingsService->getLastStanding())
            $next_standing = $standingsService->new_standing_id;
        $insert['temp_songi_list_not'] = $next_standing;
        unset($insert['_token']);
        
        try{
            Songitemp::insert($insert);
            $this->_code = $code;
        } catch (Exception $e) {
            //print_r($e);
            return false;
        }
        
        return true;
    }
    
    public function getCode()
    {
        return $this->_code;
    }
    
    public function getTempByCode(string $code)
    {
        $songi_temp = Songitemp
                ::where('temp_songi_list_code', '=', $code)
                ->where('temp_songi_list_voted', '=', 0)
                ->get();
        if (count($songi_temp) > 0)
            return $songi_temp;
        else
            return false;
            
    }
    
    public function saveTempToSongi(string $code)
    {
        if (empty($songi_temp = $this->getTempByCode($code)) ) {
            echo 'Nie znaleziono pasującego aktywnego kodu';
        } else {
            foreach($songi_temp as $songi) {
                for($i=1;$i<11;$i++) {
                    $name = 'temp_songi_list_id_'.$i;
                    $songi->{$name}.'<br />';
                    if ($songi->{$name} > 0) {
                        Songi::where('songi_list_place', $songi->{$name})
                                ->increment('songi_list_points', (11-$i));
                        //echo $name.'<br />';
                    }
                }
                Songitemp::where('temp_songi_list_code', $code)->update(['temp_songi_list_voted'=>1]);
            }
        }
    }
}