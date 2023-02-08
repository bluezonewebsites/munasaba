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
    public function toArray()
    {
        $data["id"] = $this->id;
        $data["cat_id"]=$this->cat_id;
        $data["sub_cat_id"]=$this->sub_cat_id;
        $data["uid"]=$this->uid;
        $data["name"]=$this->name;
        $data["price"]=$this->price;
        $data["created_at"]=$this->created_at;
        $data["loc"]=$this->loc;
        $data["country_id"]=$this->country_id;
        $data["city_id"]=$this->city_id;
        $data["region_id"]=$this->region_id;
        $data["lat"]=$this->lat;
        $data["lng"]=$this->lng;
        $data["descr"]=$this-> descr;
        $data["phone"]=$this-> phone;
        $data["wts"]=$this-> wts;
        $data["has_chat"]=$this->has_chat;
        $data["has_wts"]=$this->has_wts;
        $data["has_phone"]=$this->has_phone;
        $data["amount"]=$this->amount;
        $data["tajeer_or_sell"]=$this->tajeer_or_sell;
        $data["views"]=$this->views;
        $data["calls"]=$this->calls;
        $data["errors"]=$this->errors;
        $data["duration_use_name"]=$this->duration_use_name;
        $data["duration_use"]=$this->duration_use;
        $data["sell_cost"]=$this->sell_cost;
        $data["end_date"]=$this-> end_date;
        $data["brand_id"]=$this->brand_id;
        $data["material_id"]=$this->material_id;
        $data["color"]=$this-> color;
        $data["color_name"]=$this-> color_name;
        $data["prod_size"]=$this-> prod_size;
        $data["img"]=$this->img;
        $data["deleted"]=$this-> deleted;
        $data["updated_at"]=$this->updated_at;
        $data["deleted_at"]=$this-> deleted_at;
        $data["main_cat_name"]=$this->category ->name_ar;
        $data["sub_cat_name"]=$this->subCategory ->name_ar;
        $img=$this->firstprodImage->first();
       $data["prods_image"] = $img? $img->img: '';
       $data["mtype"] =  $img? $img->mtype: '';
       $data["user_name"] = $this->user->name??'';
       $data["user_last_name"] = $this->user->last_name??'';
       $data["user_verified"] = $this->user->verified??'';
       $data["countries_name_ar"] = $this->country->name_ar??'';
       $data["countries_name_en"] = $this->country->name_en??'';
       $data["countries_currency_ar"] = $this->country->currency_ar??'';
       $data["countries_currency_en"] = $this->country->currency_en??'';
       $data["cities_name_ar"] = $this->city->name_ar??'';
       $data["cities_name_en"] = $this->city->name_en??'';
       $data["regions_name_ar"] = $this->region->name_ar??'';
       $data["regions_name_en"] = $this->region->name_en??'';
       $data["comments"] = $this-> prodRate->count();
//        $data["is_block"] = $this->is_block();
        return $data;
    }
    protected $appends=[
        'image',
    ];
    public function getImageAttribute()
    {
        if ($this->img != null || $this->img != ' '){
            return asset('image/' . $this->img);
        }else{
           $img= $this-> prodImage ->first();
            return $img ?$img->img : null ;
        }

    }
    public function category()
    {
        return $this->belongsTo(Category::class,'cat_id','id');
    }
    public function subCategory()
    {
        return $this->belongsTo(Category::class,'sub_cat_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'uid','id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class)
            ->withDefault(['id'=>0,
                'name_ar'=>'',
                'name_en'=>'',
                'currency_ar'=>'',
                'currency_en'=>'',
            ]);
    }
    public function city()
    {
        return $this->belongsTo(City::class)->withDefault(['id'=>0,'name_ar'=>'','name_en'=>'']);
    }
    public function region()
    {
        return $this->belongsTo(Region::class)->withDefault(['id'=>0,'name_ar'=>'','name_en'=>'']);
    }
    public function prodImage()
    {
        return $this->hasMany(ProdImage::class);
    }

    public function firstprodImage()
    {
        return $this->hasMany(ProdImage::class)->select('img','mtype');
    }
    public function prodRate()
    {
        return $this->hasMany(ProdRate::class);
    }
    public function comments()
    {
        return $this->hasMany(CommentOnProd::class);
    }


}
