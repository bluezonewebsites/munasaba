<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBlocked extends Model
{
    use HasFactory;
    protected $table='user_blocked';
    protected $fillable = [
        "to_uid",
        "from_uid",
    ];
    public function user(){
        return $this->belongsTo(User::class,'from_uid','id');
    }
    public function block_user(){
        return $this->belongsTo(User::class,'to_uid','id');
    }
}
