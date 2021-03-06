<?php

namespace App\Services;
use App\Models\Standings;
use App\Models\Songi;
use App\Models\Bands;

class StandingsService
{
    protected $_new_standing_txt = '';
    protected $_new_standing_array = array();
    
    public $last_standing = null;
    public $new_standing_id = 1;
    
    public function createStanding()
    {
        $this->getLastStanding();
        
        $today = date("Y-m-d");
        if ($today != $this->last_standing->stand_date) {
            
            $songi = Songi::where('songi_list_place', '>', 0)
                        ->orderby('songi_list_points', 'desc')
                        ->get();
            $place = 1;
            foreach($songi as $song) {
                $this->_new_standing_array[$place++] = $song;
                $last_place = 'N';
                if (!empty($song->songi_list_place) )
                    $last_place = $song->songi_list_place;
                $this->_new_standing_txt .= $song->songi_list_id.' ('.$last_place.')|';
                echo $place.';'.$song->songi_list_title.'<br />';
                //$z = $place++;
                //echo $z.';'.$place.'<br />';
            }
            
            if ( count($this->_new_standing_array) > 0) {
                Songi::where('songi_list_place', '>',0)
                        ->update(['songi_list_place'=>0]);
                foreach($this->_new_standing_array as $place => $song) {
                    $add_points = $song->total_points;
                    if ($place < 31) {
                        $add_points += 31 - $place;
                    }
                    $update_array = array(
                                'songi_list_points'=>((101 - $place)),
                                'songi_list_place'=>$place,
                                'total_points'=>$add_points);
                    if ($place == 1)
                        $update_array['first_places'] = $song->first_places + 1;
                    if (empty($song->first_standing) ) {
                        $update_array['first_standing'] = $this->new_standing_id;
                        $update_array['high_standing'] = $place;
                    } else {
                        if ($song->high_standing > $place)
                            $update_array['high_standing'] = $place;
                    }
                        
                    Songi::where('songi_list_id', '=', $song->songi_list_id)
                            ->update($update_array);
                }
                $standingsInsertArray = array('id'=>$this->new_standing_id,
                                'stand_date'=>date("Y-m-d"),
                                'standing'=>$this->_new_standing_txt);
                Standings::insert($standingsInsertArray);
            }
        }
    }
    
    public function getLastStanding() : bool
    {
        $stand = Standings::orderBy('id', 'desc')->first();
       
       if (!empty($stand->id) ) {
           $this->last_standing = $stand;
           $this->new_standing_id  = $stand->id + 1;
           
           return true;
       }
       
       return false;
    }

    public function getBands()
    {
        return Bands::orderby('band_name', 'asc')->get();
    }
    
    public function insertSongi(array $insert) : bool
    {
        try {
            Songi::insert($insert);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function getStanding(int $id)
    {
        if ($standing = Standings::find($id)) {
            
            $standing_ids = array();
            $standing_array = array('id'=>$id, 
                            'stand_date'=>$standing->stand_date,
                            'standing_data'=>array());
            
            $standing_data = array();
            $place = 1;
            
            foreach(explode('|', $standing->standing) as $s) {
                $pos = strpos($s,' ');
                $s_id = substr($s,0,$pos);
                $standing_ids[] = $s_id;
                $standing_data[$s_id]['previous'] = substr($s,$pos+1);
                $standing_data[$s_id]['place'] = $place++;
            }
            //echo $standing_ids.'<br />';
            
            $songi = Songi::whereIn('songi_list_id', $standing_ids)
                        ->join('bands', 'bands.band_id', '=', 'songi_list.songi_list_band_id')->get();
            foreach($songi as $s) {
                $standing_data[$s->songi_list_id]['name'] = $s->songi_list_title.' - '.$s->band_name;
                $standing_array['standing_data'][$standing_data[$s->songi_list_id]['place']] = $standing_data[$s->songi_list_id];
            }
            
            return $standing_array;
        }
         
        return false;
    }
    
    public function getStandingsList(int $page = 1)
    {
        if ($page < 1) $page = 1;
        $skip = $page*20 - 20;
        
        return Standings::select('id', 'stand_date')
                    ->orderby('stand_date', 'desc')
                    ->skip($skip)
                    ->take(20)
                    ->get();
    }

    public function countLittlePoints()
    {
        $this->getLastStanding();
        for($i = 0; $i < $this->last_standing->id; $i++) {
            $standing = $this->getStanding(($i+1));
            $places = count($standing['standing_data']);
            echo $places.'<br />';

            print_r($standing['standing_data']);
            foreach($standing['standing_data'] as $k => $v) {
                if ( $v['place'] > 20 ) {
                    if (empty($little_points[$v['name']]))
                        $little_points[$v['name']] = 0;
                    $little_points[$v['name']] += ($places + 1 - $v['place']);
                }
            }
            echo '<br />'.print_r($little_points, true);
        }
    }
}
