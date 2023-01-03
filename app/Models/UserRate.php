<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRate extends Model
{
    use HasFactory;
    protected $table='user_rates';

    protected $fillable = [
        'user_rated_id',
        'uid',
        'rate',
        'comment',
    ];

    public function user(){
        return $this->belongsTo(User::class,'uid','id');
    }
    public function user_rate(){
        return $this->belongsTo(User::class,'user_rate_id','id');
    }
}
