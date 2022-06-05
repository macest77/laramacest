<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LottoDoubles extends Model
{
    use HasFactory;
    
    protected $table = 'lotto_doubles';
    
    protected $primaryKey = 'id';
    
    public $incrementing = true;
    
    public $timestamps = false;
    
    protected $fillable = [
           'id',
           'numbers',
           'total_hits',
           'descript',
           'last',
        ];
        
    
}
