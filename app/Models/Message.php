<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
     use HasFactory;
     
    protected $table='msgs';
    protected $guarded=[];
    protected $appends=[
        'full_path_image',
    ];
    public function getFullPathImageAttribute()
    {
        return asset('image/' . $this->image);
    }
   
}
