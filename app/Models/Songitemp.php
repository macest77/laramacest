<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Songitemp extends Model
{
    use HasFactory;
    
    protected $table = 'temp_songi_list';
    
    protected $primaryKey = 'temp_songi_list_mail';
    
    public $incrementing = false;
    
    public $timestamps = false;
    
    protected $fillable = [
            'temp_songi_list_mail',
            'temp_songi_list_not',
            'temp_songi_list_code',
            'temp_songi_list_date',
            'temp_songi_list_voted',
            'temp_songi_list_id_1',
            'temp_songi_list_id_2',
            'temp_songi_list_id_3',
            'temp_songi_list_id_4',
            'temp_songi_list_id_5',
            'temp_songi_list_id_6',
            'temp_songi_list_id_7',
            'temp_songi_list_id_8',
            'temp_songi_list_id_9',
            'temp_songi_list_id_10',
        ];
        
    
}
