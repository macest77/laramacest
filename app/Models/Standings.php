<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standings extends Model
{
    use HasFactory;
    
    protected $table = 'standings';
    
    protected $primaryKey = 'id';
    
    public $incrementing = true;
    
    public $timestamps = false;
    
    protected $fillable = [
           'stand_date',
           'standing',
        ];
        
    
}
