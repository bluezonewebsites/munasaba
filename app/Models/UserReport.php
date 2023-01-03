<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReport extends Model
{
    use HasFactory;
    protected $table='user_report';

    protected $fillable = [
        'from_uid',
        'uid',
        'reson',
    ];

    public function user(){
        return $this->belongsTo(User::class,'uid','id');
    }
    public function from_user(){
        return $this->belongsTo(User::class,'from_uid','id');
    }
}
