<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Room extends Model
{

    use HasFactory,SoftDeletes;


    protected $table='rooms';

    protected $guarded=[];

     public function toArray()
    {
        $lang = app()->getLocale();
        $data['id'] = $this->id;
        $data['user1'] = $this->user1;
        $data['user2'] = $this->user2;
        $data['blocked'] = $this->blocked;
        $data['user_make_block'] = $this->user_make_block;
        $data['user_delete_chat'] = $this->user_delete_chat;
        $data['deleted'] = $this->deleted;
        $data['date'] = $this->date;
        $data["unseen_count"] = $this->messages->where('seen',0)->where('rid',Auth::id())->count();
         if ($this->user1 == Auth::id())
            $user =$this->thisusers2;
        else
            $user = $this->thisusers1;

        $data["user"] = $user;
        $data['messages'] = $this->messages->sortByDesc('id')->first();
        $data["unseen_count"] = $this->messages->where('seen',0)->where('rid',Auth::id())->count();
        return $data;
    }



    public function thisusers2()
    {
        return $this->hasMany(User::class,'id','user2');
    }


    public function thisusers1()
    {
        return $this->hasMany(User::class,'id','user1');
    }



     public function messages()
    {
        return $this->hasMany(Message::class,'room_id','id');
    }


}
