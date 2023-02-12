<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;

    protected $table='followers';

    protected $fillable = [
        'user_id',
        'to_id',
    ];
    public function from_user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'to_id','id');
    }
}
