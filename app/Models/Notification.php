<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
     use HasFactory;

    protected $table='nots';
    protected $guarded=[];
    public function toArray()
    {
        $data['id'] = $this->id;
        $data['uid'] = $this->uid;
        $data['fid'] = $this->fid;
        $data['ntype'] = $this->ntype;
        $data['ndate'] = $this->ndate;
        $data['oid'] = $this->oid;
        $data['ncontent'] = $this->ncontent;
        $data['nfrom'] = $this->nfrom;
        $data['nto'] = $this->nto;
        $data['usersend'] = $this->usersend;
        $data['userf'] = $this->userf;
        return $data;
    }
//    public function getIsFAttribute()
//    {
//        return asset('image/' . $this->img);
//    }
    public function userf()
    {
        return $this->hasMany(User::class,'id','fid');
    }
    public function usersend()
    {
        return $this->hasMany(User::class,'id','uid');
    }
}
