<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table='user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'pass',
        'last_name',
        'username',
        'mobile',
        'country_id',
        'city_id',
        'region_id',
        'pic',
        'cover',
        'verified',
        'blocked',
        'bio',
        'nots',
        'regid'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends=[
        'image',
    ];
    public function getImageAttribute()
    {
        return asset('image/' . $this->pic);
    }
    public function user_block_from(){
        return $this->hasMany(UserBlocked::class,'from_uid','id');
    }
    public function user_block_to(){
        return $this->hasOne(UserBlocked::class,'to_uid','id');
    }

}
