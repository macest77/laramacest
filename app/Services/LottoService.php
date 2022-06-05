<?php

namespace App\Services;

use App\Models\Lotto;
use App\Models\Duzylotek;
use App\Models\LottoDoubles;
use DB;

class LottoService
{
    private static function ownsort($aa, $bb) {
       $a = $aa['last'];
       $b = $bb['last'];
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
    
    private function checkDoubleDraws() {
        $draws_data = Lotto::orderby('draw_date', 'desc')->take(40)->get();//where('draw_numbers', 'like', '|'.$nb.'|')->orderby('draw_date', 'desc');
        $draw_id = 0; $draw_taken = 0; $draw_found = 0;
        $tmp_array = array('last'=>0, 'prev'=>0);
        $t_draw_numbers = array('last'=>0, 'draw_taken'=>0, 'double'=>$tmp_array, 'tripple'=>$tmp_array, 'double_d1'=>$tmp_array, 'double_d2'=>$tmp_array, 'double_d3'=>$tmp_array);
        $draw_numbers = array();
        $draws = array();
        $t_draw = 40;
        foreach ($draws_data as $d) {
            $draws[$t_draw] = $d;
            $t_draw--;
        }
        for($draw_id = 1;$draw_id < 41; $draw_id++) {
            $d = $draws[$draw_id];
        //foreach ($draws as $d) {
            //$draw_id++;
            //if ($draw_id == 1) echo $d->draw_date.'<br />';
            $draw_array = explode('|', $d->draw_numbers);
            for($i=1;$i<7;$i++) {
                if (empty($draw_numbers[$draw_array[$i]]) )
                    $draw_numbers[$draw_array[$i]] = $t_draw_numbers;
                if ( $draw_numbers[$draw_array[$i]]['last'] > 0 ) {
                    $diff = $draw_id - $draw_numbers[$draw_array[$i]]['last'];
                    if ($diff == 1) {
                        if ($draw_array[$i] == 14) {
                            echo $draw_id.'; '.$draw_numbers[$draw_array[$i]]['last'].'; '.$draw_numbers[$draw_array[$i]]['double']['last'].'; '.$draw_numbers[$draw_array[$i]]['double']['prev'].'<br />';
                        }
                        if ($draw_numbers[$draw_array[$i]]['double']['last'] == ($draw_id - 2 ) ) {
                            $draw_numbers[$draw_array[$i]]['tripple']['prev'] = $draw_numbers[$draw_array[$i]]['tripple']['last'];
                            $draw_numbers[$draw_array[$i]]['tripple']['last'] = $draw_id;
                        } else
                            $draw_numbers[$draw_array[$i]]['double']['prev'] = $draw_numbers[$draw_array[$i]]['double']['last'];
                        $draw_numbers[$draw_array[$i]]['double']['last'] = $draw_id - 1;
                    }
                    if ($diff == 2) {
                        $draw_numbers[$draw_array[$i]]['double_d1']['prev'] = $draw_numbers[$draw_array[$i]]['double_d1']['last'];
                        $draw_numbers[$draw_array[$i]]['double_d1']['last'] = $draw_id - 2;
                    }
                    if ($diff == 3) {
                        $draw_numbers[$draw_array[$i]]['double_d2']['prev'] = $draw_numbers[$draw_array[$i]]['double_d2']['last'];
                        $draw_numbers[$draw_array[$i]]['double_d2']['last'] = $draw_id - 2;
                    }
                    if ($diff == 4) {
                        $draw_numbers[$draw_array[$i]]['double_d3']['prev'] = $draw_numbers[$draw_array[$i]]['double_d3']['last'];
                        $draw_numbers[$draw_array[$i]]['double_d3']['last'] = $draw_id - 2;
                    }
                }
                $draw_numbers[$draw_array[$i]]['last'] = $draw_id;
                $draw_numbers[$draw_array[$i]]['draw_taken']++;
            }
        }//echo 'd - '.$draw_id.'<br />';
        return $draw_numbers;
        foreach($draw_numbers as $nb => $nb_array) {
            //echo '<br />'.$nb.' ;'; print_r($nb_array);
        } //exit;
    }
    
    public function suggested() {
        
       $last = Lotto::orderBy('draw_date', 'desc')->first();
       
       if (empty($last->next_suggested) ) {
           $dns = $this->checkDoubleDraws();
           $count = Lotto::count();
           $hits = Duzylotek::all();
           
           $byLastDate = array();
           $numbers = array();
           $draws = 0;
           $counted = 0;
           $suggested = '|';
           $suggestedList = array();
           $suggestedArray = array();
           $additionals = array('doubled'=>array(), 'd1'=>array(), 'd2'=>array(), 'd3'=>array(), 'short_avg'=>array() );
           $shortavgs = array();
           
           foreach($hits as $h) {
               if (empty($byLastDate[$h->last]) ) 
                    $byLastDate[$h->last] = array();
                $byLastDate[$h->last][$h->id] = $h->count;
                $avg = $count / $h->count;
                $avg = round($avg,2);
                
                $numbers[$h->id] = array('avg'=>$avg, 'last'=>$h->last);
           }
           
           for($i=0;$i<120;$i++) {
               $date = date("Y-m-d", strtotime("-$i days"));
               $dateD = date("D", strtotime($date));
                if (in_array($dateD, array('Tue', 'Thu', 'Sat') )) {
                    $draws++;
                    if (isset($byLastDate[$date]) ) {
                        foreach($byLastDate[$date] as $id => $c) {
                            $counted++;
                            $a = $numbers[$id]['avg'];
                            $now = $draws - round($a,0);
                            if ($numbers[$id]['avg'] <= $draws) {
                                $t = '';
                                if ($now == 0) {
                                    $t = ' xxx';
                                    //$suggested .= $id.'|';
                                    $suggestedArray[0][$id] = array('avg'=>$numbers[$id]['avg'], 'now'=>$now, 'draws'=>$draws, 'last'=>($numbers[$id]['avg']-$draws));
                                }
                                echo "$draws : $id (avg: $a / $now)$t - ".(isset($dns[$id])?print_r($dns[$id], true):"there is no index $id in dns").'<br /><br />';
                            } else {
                                $t = '';
                                if ($now == 0) {
                                    $t = ' ...';
                                    //$suggested .= $id.'|';
                                    $suggestedArray[1][$id] = array('avg'=>$numbers[$id]['avg'], 'now'=>$now, 'draws'=>$draws, 'last'=>($numbers[$id]['avg']-$draws));
                                } elseif ($now == (-1)) {
                                    $suggestedArray[2][$id] = array('avg'=>$numbers[$id]['avg'], 'now'=>$now, 'draws'=>$draws, 'last'=>($numbers[$id]['avg']-$draws));
                                }
                                //$a = $numbers[$id]['avg'];
                                echo "---$draws : $id (avg: $a / $now)$t - ".(isset($dns[$id])?print_r($dns[$id], true):"there is no index $id in dns").'<br /><br />';
                            }
                            //print_r($numbers[$id]);exit;
                            //$additionals = array('doubled'=>array(), 'd1'=>array(), 'd2'=>array(), 'd3'=>array());
                            if (isset($dns[$id]) ) {
                                $short_avg = 40 / $dns[$id]['draw_taken'];
                                $shortavgs[$id] = round($short_avg,2);
                                echo 'add: '.$id.'; '.round($short_avg,2).'<br />';
                                $a = $dns[$id];
                                if ( $a['last'] == 40 && ($a['double']['last'] > 20 || $a['tripple']['last'] == ($a['double']['last']+1)) )
                                    $additionals['doubled'][] = $id;
                                if ( $a['last'] == 39 && $a['double_d1'] > 20) {
                                    $additionals['d1'][] = $id;
                                    echo 39 . '; '.$id.'; '.print_r($a, true).'<br />';
                                }
                                if ( $a['last'] == 38 && $a['double_d2'] > 20)
                                    $additionals['d2'][] = $id;
                                if ( $a['last'] == 37 && $a['double_d3'] > 20)
                                    $additionals['d3'][] = $id;
                                if ( ($a['last'] + round($short_avg,0) ) == 41 )
                                    $additionals['short_avg'][] = $id;
                            }
                        }
                    }
                }
           }
           
           if (count($suggestedArray) > 0) {
               $tmpCount = 0;
               for($i=0;$i<(max(array_keys($suggestedArray))+1);$i++) {
               //foreach ($suggestedArray as $sKey => $sugg) {
                    if (isset($suggestedArray[$i]) ) {
                        $sugg = $suggestedArray[$i];
                       uasort($sugg, array($this, 'ownsort'));
                       foreach($sugg as $nr => $d) {
                           
                           if ($tmpCount < 6) {
                               $suggested .= $nr.'|';
                               $suggestedList[] = $nr;
                           }
                           $tmpCount++;
                       }
                       echo $tmpCount.'<br />';
                   }
               }
           }
           if (count($suggestedList) < 6 && count($additionals['short_avg']) > 0) {
               foreach($additionals['short_avg'] as $nb) {
                   if (count($suggestedList) < 6 && !in_array($nb, $suggestedList) ) {
                       $suggested .= $nb.'|';
                       $suggestedList[] = $nb;
                   }
               }
           }
           if (count($suggestedList) < 6 && count($additionals['doubled']) > 0) {
               foreach($additionals['doubled'] as $nb) {
                   if (count($suggestedList) < 6 && !in_array($nb, $suggestedList) ) {
                       $suggested .= $nb.'|';
                       $suggestedList[] = $nb;
                   }
               }
           }
           if (count($suggestedList) < 6 && count($additionals['doubled']) > 0) {
               foreach($additionals['doubled'] as $nb) {
                   if (count($suggestedList) < 6 && !in_array($nb, $suggestedList) ) {
                       $suggested .= $nb.'|';
                       $suggestedList[] = $nb;
                   }
               }
           }
       if (empty($last->next_suggested) ) {
           echo 'update';
           Lotto::where('id', $last->id)->update(['next_suggested'=>$suggested]);
       }
           echo '<br />'.$draws.'; '.$counted.' -> '.$suggested.'<br />';
           print_r($suggestedArray);echo '<br />'.print_r($additionals, true).'<br />'.print_r($shortavgs, true);
           
           return $suggested;
       }
       return $last->next_suggested;
    }
    
    public function fillLottoDoubles()
    {
        $last_draw = LottoDoubles::orderby('last', 'desc')->take(1)->get();
        //echo $last_draw[0]->last.'<br />';
        $draws = Lotto::where('draw_date', '>', $last_draw[0]->last)->orderby('draw_date', 'asc')->get();
        //echo 'draws-count: '.$draws->count().'<br />';
        if ($draws->count() > 0) {
            //echo 'draw-date: '.$draws[0]->draw_date.'<br />';
            $d_count = 0;
            foreach($draws as $d) {
                if ($d_count < 11) {
                $d_date = $draws[$d_count]->draw_date;
                //echo 'd-draw_numbers: '.$d->draw_numbers.'<br />';
                $expl = explode('|', $d->draw_numbers);
                $numbers_list = '';
                $combinations = array(2 => array(), array(), array(), array(), array($expl));
                for($i=1;$i<6;$i++) {
                    for($a=($i+1);$a<7;$a++) {
                        if ($a == ($i+1)) {
                            for($c=2;$c<7;$c++) {
                                if ($c <= (7-$i) )
                                    $combinations[$c][$expl[$i]] = array();
                            }
                        }
                        $combinations[2][$expl[$i]][] = $expl[$a];
                        if ($a < 6) {
                            $combinations[3][$expl[$i]][$expl[$a]] = array();
                            for($b=($a+1);$b<7;$b++) {
                                $combinations[3][$expl[$i]][$expl[$a]][] = $expl[$b];
                            }
                        }
                        if ($a < 5) {
                            $combinations[4][$expl[$i]][$expl[$a]] = array();
                            for($b=($a+1);$b<6;$b++) {
                                $combinations[4][$expl[$i]][$expl[$a]][$expl[$b]] = array();
                                for($c=($b+1);$c<7;$c++) {
                                    $combinations[4][$expl[$i]][$expl[$a]][$expl[$b]][] = $expl[$c];
                                }
                            }
                        }
                        if ($a < 4) {
                            $combinations[5][$expl[$i]][$expl[$a]] = array();
                            for($b=($a+1);$b<5;$b++) {
                                $combinations[5][$expl[$i]][$expl[$a]][$expl[$b]] = array();
                                for($c=($b+1);$c<6;$c++) {
                                   if (empty($combinations[5][$expl[$i]][$expl[$a]][$expl[$b]][$expl[$c]]))
                                    $combinations[5][$expl[$i]][$expl[$a]][$expl[$b]][$expl[$c]] = array();
                                    $combinations[5][$expl[$i]][$expl[$a]][$expl[$b]][$expl[$c]][] = $expl[$c+1];
                                }
                            }
                        }
                    }
                }
                $double_draws = LottoDoubles::where('numbers', 'like', '%|'.$expl[1].'|%')
                                            ->orWhere('numbers', 'like', '%|'.$expl[2].'|%')
                                            ->orWhere('numbers', 'like', '%|'.$expl[3].'|%')
                                            ->orWhere('numbers', 'like', '%|'.$expl[4].'|%')
                                            ->orWhere('numbers', 'like', '%|'.$expl[5].'|%')
                                            ->orWhere('numbers', 'like', '%|'.$expl[6].'|%')
                                            ->get();
                $double_draws_array = array();
                foreach($double_draws as $dd) {
                    $double_draws_array[$dd->numbers] = $dd;
                }
                //echo print_r($expl, true).'<br /><br />'.print_r($combinations, true).'<br />';
                foreach($combinations as $c => $numbers) {//echo '<br />c: '.$c.'<br />';
                    foreach($numbers as $n => $numbs) {//echo 'n: '.$n.' ';
                        if (is_array($numbs)) {
                            foreach($numbs as $n2 => $numbs2) {//echo $n2.' ';
                                if (is_array($numbs2)) {
                                    foreach($numbs2 as $n3 => $numbs3) {//echo $n3.' ';
                                        if (is_array($numbs3)) {
                                            foreach($numbs3 as $n4 => $numbs4) {//echo $n4.' ';
                                                if (is_array($numbs4)) {
                                                    foreach($numbs4 as $n5 => $numbs5) {//echo $n5.' ';
                                                        if (is_array($numbs5)) {
                                                        } else {
                                                            $numbers_txt = '|'.$n.'|'.$n2.'|'.$n3.'|'.$n4.'|'.$numbs5.'|';
                                                            //echo ';'.$c.': |'.$n.'|'.$n2.'|'.$n3.'|'.$n4.'|'.$numbs5.'| ; ';
                                                            if (isset($double_draws_array[$numbers_txt]) ) {
                                                                LottoDoubles::where('id', $double_draws_array[$numbers_txt]->id)->update(['total_hits'=>($double_draws_array[$numbers_txt]->total_hits+1), 'last'=>$d_date]);
                                                            } else {
                                                                DB::insert('insert into lotto_doubles (numbers, descript, last) values(?, ?, ?)',[$numbers_txt, $c, $d_date]);
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    $numbers_txt = '|'.$n.'|'.$n2.'|'.$n3.'|'.$numbs4.'|';
                                                    //echo ';'.$c.': |'.$n.'|'.$n2.'|'.$n3.'|'.$numbs4.'| ; ';
                                                    if (isset($double_draws_array[$numbers_txt]) ) {
                                                        LottoDoubles::where('id', $double_draws_array[$numbers_txt]->id)->update(['total_hits'=>($double_draws_array[$numbers_txt]->total_hits+1), 'last'=>$d_date]);
                                                    } else {
                                                        DB::insert('insert into lotto_doubles (numbers, descript, last) values(?, ?, ?)',[$numbers_txt, $c, $d_date]);
                                                    }
                                                }
                                            }
                                        } else {
                                            $numbers_txt = '|'.$n.'|'.$n2.'|'.$numbs3.'|';
                                            //echo ';'.$c.': |'.$n.'|'.$n2.'|'.$numbs3.'| ; ';
                                            if (isset($double_draws_array[$numbers_txt]) ) {
                                                LottoDoubles::where('id', $double_draws_array[$numbers_txt]->id)->update(['total_hits'=>($double_draws_array[$numbers_txt]->total_hits+1), 'last'=>$d_date]);
                                            } else {
                                                DB::insert('insert into lotto_doubles (numbers, descript, last) values(?, ?, ?)',[$numbers_txt, $c, $d_date]);
                                            }
                                        }
                                    }
                                } else {
                                    if ($c < 6) {
                                        $numbers_txt = '|'.$n.'|'.$numbs2.'|';
                                        //echo ';'.$c.': |'.$n.'|'.$numbs2.'| ; ';
                                        if (isset($double_draws_array[$numbers_txt]) ) {
                                            LottoDoubles::where('id', $double_draws_array[$numbers_txt]->id)->update(['total_hits'=>($double_draws_array[$numbers_txt]->total_hits+1), 'last'=>$d_date]);
                                        } else {
                                            DB::insert('insert into lotto_doubles (numbers, descript, last) values(?, ?, ?)',[$numbers_txt, $c, $d_date]);
                                        }
                                    }
                                }
                            }
                        } else {
                            //echo $numbs;
                        }
                    }
                }
                $d_count++;
                //if ($d_count > 10)
                    //break;
                }
            }
        }
    }
}
