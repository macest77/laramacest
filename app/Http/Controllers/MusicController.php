<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Reviews;
use App\Models\Config;
use App\Services\SongiService;
use App\Services\MailingService;
use App\Services\StandingsService;

class MusicController extends Controller
{
    public function index() {
        $reviews = Reviews::orderBy('added', 'desc')
                    ->leftJoin('records_news', 'records_news.rec_id', '=', 'reviews.record_id')
                    ->leftJoin('bands', 'bands.band_id', '=', 'records_news.band_id')
                    ->take(20)->get();
        
        return view('reviews_view',['reviews'=>$reviews]);
    }
   
    public function list() {
        $songi = (new SongiService)->getSongiList();
        
        return view('songi_view',['songi'=>$songi]);
    }
    
    public function listpost(Request $request)
    {
        $errors = array();
        $songiService = (new SongiService);
        
        if ($songiService->saveSongi($request->datta)) {
            $mailingService = (new MailingService);
            $errors[] = $mailingService->basic_email($request->datta['temp_songi_list_mail'], $songiService->getCode());
        } else
            $errors[] = 'Błąd zapisu głosów';
        $songi = $songiService->getSongiList();
        
        foreach ($errors as $error) {
            echo $error.'<br />';
        }
        
        //return view('songi_post_view',['songi'=>$songi, 'errors'=>$errors]);
    }
    
    public function listform(array $request)
    {
        print_r($request);
    }
    
    public function confirmCode(Request $request)
    {
        if (!empty($request->code) ) {
            $songiService = (new SongiService);
            $songiService->saveTempToSongi($request->code);
            
            echo "LARAMACEST - Lista Hard'n'heavy<br /><br />Dziękujemy - twoje głosy zostały dopisane<br />
            <a href='https://laravel.macest.slaskdatacenter.pl/lista-hard-n-heavy'>Najnowsze notowanie listy</a>";
        } else
            echo 'Błędne wywołanie';
    }
    
    public function postchart(Request $request)
    {
        //echo '.'.$request->admin_password.'.';
        $pass = md5($request->admin_password);
        //echo '.'.$request->admin_password.'.'.$pass;exit;
        
        $result = Config::where('id', '=', 'list_admin')->where('config', '=', $pass)->first();
        
        if ($result) {
            echo 'ok';
            $standingsService = new StandingsService;
            $standingsService->createStanding();
        } else {
            echo $pass;
            return view('chart_view');
        }
    }

    public function chart()
    {
        return view('chart_view');
    }
    
    public function listing($id)
    {
        $id = (int)$id;
        
        $standing_service = new StandingsService;
        if ($standing = $standing_service->getStanding($id)) {
            $listings = array();
        } else {
            $standing = array();
            $listings = $standing_service->getStandingsList(1);
        }
        
        return view('listing_view',['standing'=>$standing, 'listings'=>$listings]);
    }
}