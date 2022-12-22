<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table='cats';
    protected $fillable = [
        'name_ar',
        'name_en',
        'hide',
        'slug',
        'has_scat',
        'cat_id',
    ];
    protected $appends=[
        'name',
        'full_path_image'
    ];
   
    public function getFullPathImageAttribute()
    {

        return asset('assets/category/images/'. $this->pic) ;

    }
    public function scopeHasSCat($query)
    {
        return $query->where('has_cat',0);
    }
    public function prod()
    {
        return $this->hasMany(Prod::class,'cat_id','id');
    }

    public function getNameAttribute()
    {
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
    public function scopeActive($query)
    {
        return $query->where('hide', 0);
    }
    
}
