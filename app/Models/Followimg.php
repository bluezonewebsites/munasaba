<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Followimg extends Model
{
    use HasFactory;
    protected $table='followings';

    protected $fillable = [
        'uid',
        'fid',
    ];
    public function from_user(){
        return $this->belongsTo(User::class,'fid','id');
    }
    public function to_user(){
        return $this->belongsTo(User::class,'uid','id');
    }
}
