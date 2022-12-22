<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        "name_ar",
        "name_en",
        "lat",
        "lng",
        "country_id",
    ];
    protected $appends=[
        'name'
    ];
    public function country(){
        return $this->belongsTo(Country::class,"country_id","id");
    }
    public function getNameAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
}
