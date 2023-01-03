<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prod extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'cat_id',
        'sub_cat_id',
        'uid',
        'name',
        'price',
        'loc',
        'country_id',
        'city_id',
        'region_id',
        'lat',
        'lng',
        'descr',
        'phone',
        'wts',
        'has_chat',
        'has_wts',
        'has_phone',
        'amount',
        'tajeer_or_sell',
        'duration_use',
        'sell_cost',
        'color_name',
        'prod_size',
        'img',
    ];
    protected $appends=[
        'image',
    ];
    public function getImageAttribute()
    {
        return asset('image/' . $this->img);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function subCategory()
    {
        return $this->belongsTo(Category::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'uid','id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    public function prodImage()
    {
        return $this->hasMany(ProdImage::class);
    }
    public function prodRate()
    {
        return $this->hasMany(ProdRate::class);
    }
}
