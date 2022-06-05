<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Lotto;
use App\Models\Duzylotek;
use App\Services\LottoService;

class LottoController extends Controller
{
    public function index() {
      $fileTest = '/home/macest/logs/'.date("Ymd").'.txt';
      if (!file_exists($fileTest) ) {
          include 'cron.php';
          $drawsbb = (new LottoService)->fillLottoDoubles();
          $h = fopen($fileTest, "a");
          $dating = "test";
          fwrite($h, $dating);
          fclose($h);
      }
      $draws = DB::select('select * from lotto');
      $draws = Lotto::orderBy('draw_date', 'desc')->take(10)->get();
      if ($_SERVER['REMOTE_ADDR'] == '83.26.167.130' || $_SERVER['REMOTE_ADDR']=='2a01:111f:a72:6700:8da0:da91:c6c8:e98b') {
          $drawsbb = (new LottoService)->fillLottoDoubles();
      }
      return view('lotto_view',['draws'=>$draws]);
   }
   
   public function getdraw() {
       $ch = curl_init("https://www.lotto.pl/lotto/wyniki-i-wygrane");
       
       ob_start();

        curl_exec($ch);
        curl_close($ch);
        $retrievedhtml = ob_get_contents();
        ob_end_clean();
        
        $txt = substr($retrievedhtml, strpos($retrievedhtml, 'Ostatnie losowania'));
        $txt = substr($txt, strpos($txt, 'godz.')-12);
        $date = date("Y-m-d", strtotime(substr($txt,0,10)));
        $dateD = date("D", strtotime($date));
        //$draws = DB::select('select * from lotto where draw_date = (?)', [$date]);
        $draws = Lotto::where('draw_date', $date)->get();
        if (count($draws) < 1 && in_array($dateD, array('Tue', 'Thu', 'Sat') )) {
            $txt = substr($txt, strpos($txt, 'result-item__balls-box'));
            $txt = substr($txt, strpos($txt, '>')+1);
            $numbers = '|';
            for($i=0;$i<6;$i++) {
                echo $i.';'.substr($txt, 0,2).'<br />';
                $txt = substr($txt, strpos($txt, '>')+1);
                $numbers .= substr($txt, 0,strpos($txt, '<')).'|';
                $txt = substr($txt, strpos($txt, '<div'));;
            }
            echo $date.'; ';
            $numbers = str_replace(array(' ', '                                                ', '/n', '
'), '', $numbers);
            DB::insert('insert into lotto (draw_date) values(?)',[$date]);
            DB::update('update lotto set draw_numbers = ? where draw_date = ?',[$numbers,$date]);
            //$draws = DB::select('select * from lotto where draw_date = (?)', [$date]);
            //echo '; '.$txt;
        }
        $result = Lotto::orderBy('draw_date', 'desc')->first();
        //$result = DB::select('select * from lotto order by draw_date desc');
        
        return view('getdraw_view',['result'=>$result]);
   }
   
   public function getdrawdate() {
       
       for($i=1;$i<(365*5);$i++) {
            $date = date("Y-m-d", strtotime("-$i days"));
            $dateD = date("D", strtotime($date));
            $draws = Lotto::where('draw_date', $date)->get();echo $date.'<br />';
            if (count($draws) < 1 && in_array($dateD, array('Tue', 'Thu', 'Sat') )) {echo 'sss<br />';
                $ch = curl_init("https://www.lotto.pl/lotto/wyniki-i-wygrane/date,$date,10");
                
                ob_start();
                curl_exec($ch);
                curl_close($ch);
                $retrievedhtml = ob_get_contents();
                ob_end_clean();
        
                $txt = substr($retrievedhtml, strpos($retrievedhtml, 'Ostatnie losowania'));
                $txt = substr($txt, strpos($txt, 'godz.')-12);
                $date = date("Y-m-d", strtotime(substr($txt,0,10)));
                $txt = substr($txt, strpos($txt, 'result-item__balls-box'));
                $txt = substr(str_replace('
                                            ','',$txt), strpos($txt, '>')+1);

                $numbers = '|';
                for($i=0;$i<6;$i++) {
                    $txt = substr($txt, strpos($txt, '>')+1);
                    $number = substr($txt, 0,strpos($txt, '<'));
                    $d = Duzylotek::where('id', $number)->first();
                    $c = (int)$d->count;
                    if ($c > 0) {
                        $c++;
                        DB::update('update duzy_lotek set count = ?, last = ? where id = ?',[$c,$date, $number]);
                        //Duzylotek::where('id', $number)->update(['count'=>$c]);
                        echo $c.'<br />';
                    } else {
                        Duzylotek::where('id', $number)->update(['count'=>1, 'last'=>$date]);
                        echo '1<br />';
                    }
                    $numbers .= $number.'|';
                    $txt = substr($txt, strpos($txt, '<div'));;
                }
                echo $date.'; ';
                $numbers = str_replace(array(' ', '                                                ', '/n', '
'), '', $numbers);
                DB::insert('insert into lotto (draw_date) values(?)',[$date]);
                DB::update('update lotto set draw_numbers = ? where draw_date = ?',[$numbers,$date]);
            }
        }
   }
   
   public function countnext() {
       $next_draw_numbers = (new LottoService)->suggested();
       
       $draws = Lotto::whereNotNull('hits')->get();
       $count = 0; $hits = 0;
       foreach ($draws as $d) {
           $count++;
           $hits += $d->hits;
       }
       $avg = round(($hits / $count),2);
       $ndn = array('next_draw_numbers' => $next_draw_numbers, 'avg'=>$avg, 'count'=>$count);
       
       return view('countnext_view',['next_draw_numbers'=>$ndn]);
   }
}

