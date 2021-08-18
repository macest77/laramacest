<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Duzylotek extends Model
{
    use HasFactory;
    
    protected $table = 'duzy_lotek';
    
    protected $primaryKey = 'id';
    
    public $incrementing = false;
    
    public $timestamps = false;
    
    protected $fillable = [
           'id',
           'count',
           'last',
        ];
}
