<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fav extends Model
{
    use HasFactory;
    protected $table='fav';

    protected $fillable = [
        'uid',
        'prod_id',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function prod(){
        return $this->belongsTo(Prod::class);
    }

}
