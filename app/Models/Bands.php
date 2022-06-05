<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bands extends Model
{
    use HasFactory;
    
    protected $table = 'bands';
    
    protected $primaryKey = 'band_id';
    
    public $incrementing = true;
    
    public $timestamps = false;
    
    protected $fillable = [
           'band_name',
        ];
        
    
}
