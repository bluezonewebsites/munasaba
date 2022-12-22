<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $fillable = [
        "name_ar",
        "name_en",
        "lat",
        "lng",
        "city_id",
    ];
    protected $appends=[
        'name'
    ];
    public function city(){
        return $this->belongsTo(City::class,"city_id","id");
    }
    public function getNameAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
}
