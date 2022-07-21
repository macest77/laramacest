<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Bands;
use App\Models\Config;
use App\Services\StandingsService;

class StandingsController extends Controller
{
    
    public function addsong()
    {
        $standingsService = (new StandingsService);
        $all_bands = $standingsService->getBands();
        
        return view('addsong_view', ['bands'=>$all_bands, 'save_infos'=>array()]);
    }
    
    public function addsongpost(Request $request)
    {
        $standingsService = (new StandingsService);
        $all_bands = $standingsService->getBands();
        $save_infos = array();
        
        $insert = array();
    
        if (!empty($request->songi_list_title)) {
            $pass = md5($request->admin_password);
            
            $result = Config::where('id', '=', 'list_admin')->where('config', '=', $pass)->get()->count();
            if ($result > 0) {
                $insert['songi_list_title'] = strip_tags($request->songi_list_title);
                $insert['songi_list_band_id'] = (int)$request->songi_list_band_id;
                $insert['songi_list_year'] = (int)$request->songi_list_year;
                if ($standingsService->insertSongi($insert))
                    $save_infos[] = 'zapisano';
                else $save_infos[] = 'wystąpił błąd przy zapisie';
            } else $save_infos[] = 'błędne hasło';
        }
            
        
        return view('addsong_view', ['bands'=>$all_bands, 'save_infos'=>$save_infos]);
    }
    
    public function addband()
    {
        
    }

    public function countlittlepoints()
    {
        $standingsService = (new StandingsService);
        $standingsService->countLittlePoints();
    }
}