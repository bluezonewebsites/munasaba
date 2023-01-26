<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdRate extends Model
{
    use HasFactory;
    protected $table='prods_rates';
    protected $guarded=[];


    protected $appends=[
        'is_like'
    ];
    public function toArray()
    {
        $lang = app()->getLocale();
        $data['id'] = $this->id;
        $data['uid'] = $this->uid;
        $data['prod_id'] = $this->prod_id;
        $data['comment'] = $this->comment;
        $data['rating'] = $this->rating;
        $data['date'] = $this->date;
        $data['created_at'] = $this->created_at;
        $data['updated_at'] = $this->updated_at;
        $data['comment_user_name'] = $this->user->name;
        $data['comment_user_last_name'] = $this->user->last_name;
        $data['comment_user_verified'] = $this->user->verified;
        $data['comment_user_pic'] = $this->user->pic;
        $data['is_like'] = $this->is_like;
        return $data;
    }
//'prods_rates.*',
//            'user.name as comment_user_name',
//            'user.last_name as comment_user_last_name',
//            'user.verified as comment_user_verified',
//            'user.pic as comment_user_pic',)->get();

    public function getIsLikeAttribute()
    {
        if(Auth()->check()){
            return $this->likes->where('uid',Auth()->id())->count();
        }
        return 0;
    }

    public function user(){
        return $this->belongsTo(User::class,'uid')->withDefault([
            'id'=>0,
            'name'=>'',
            'last_name'=>'',
            'verified'=>0,
            'pic'=>'',
        ]);
    }
    public function likes()
    {
        return $this->hasMany(LikeOnRate::class,'comment_id','id');
    }
    public function prod(){
        return $this->belongsTo(Prod::class);
    }

}
