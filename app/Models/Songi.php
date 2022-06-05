<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Songi extends Model
{
    use HasFactory;
    
    protected $table = 'songi_list';
    
    protected $primaryKey = 'songi_list_id';
    
    public $incrementing = true;
    
    public $timestamps = false;
    
    protected $fillable = [
           'songi_list_id',
           'songi_list_title',
           'songi_list_band_id',
           'songi_list_year',
           'songi_list_points',
           'songi_list_place',
        ];
        
    
}
