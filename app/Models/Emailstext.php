<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emailstext extends Model
{
    use HasFactory;
    
    protected $table = 'emailstext';
    
    protected $primaryKey = 'id';
    
    public $incrementing = false;
    
    public $timestamps = false;
    
    protected $fillable = [
            'id',
            'subject',
            'content',
        ];
        
    
}
