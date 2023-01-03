<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

     protected $fillable = [
        'quest',
        'uid',
        'country_id',
        'city_id',
        'pic',
    ];
    protected $appends=[
        'image',
    ];
    public function getImageAttribute()
    {
        return asset('/image/' . $this->pic);
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function blockedUser($query,$to_uid){
        return $query->where('uid','!=',$to_uid);
    }
}
