<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lotto extends Model
{
    use HasFactory;
    
    protected $table = 'lotto';
    
    protected $primaryKey = 'id';
    
    public $incrementing = true;
    
    public $timestamps = false;
    
    protected $fillable = [
           'id',
           'draw_date',
           'draw_numbers',
           'next_suggested',
        ];
        
    
}
