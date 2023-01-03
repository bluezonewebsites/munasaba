<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingUser extends Model
{
    use HasFactory;
    protected $table='pending_users';

    protected $fillable = [
        'country_id',
        'uid',
        'mobile',
        'account_type',
        'category',
        'document_type',
        'pic',
        'note'
    ];
    protected $appends=[
        'image',
    ];
    public function getImageAttribute()
    {
        return asset('image/' . $this->pic);
    }

    public function user(){
        return $this->belongsTo(User::class,'uid','id');
    }
    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }

}
