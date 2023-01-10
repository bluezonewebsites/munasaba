<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory ,SoftDeletes;
    protected $fillable = [
        'name_ar',
        'name_en',
        'currency_en',
        'currency_ar',
        'code',
        'hide',
        'count_phone',
        'blocked',
        'pic',
    ];
    protected $appends=[
        'name',
        'currency'
    ];
    protected $casts = [
        'id' => 'string',
    ];
    public function getNameAttribute()
    {

        return app()->getLocale()== 'ar' ? $this->name_ar : $this->name_en;

    }
    public function getCurrencyAttribute()
    {

        return app()->getLocale()== 'ar' ? $this->currency_ar : $this->currency_en;

    }
    public function scopeActive($query)
    {
        return $query->where('hide', 0);
    }
}
