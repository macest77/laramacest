<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory;
    
    protected $table = 'reviews';
    
    protected $primaryKey = 'id';
    
    public $incrementing = true;
    
    public $timestamps = false;
    
    protected $fillable = [
           'id',
           'added',
           'link',
           'note',
           'description',
        ];
        
    
}
