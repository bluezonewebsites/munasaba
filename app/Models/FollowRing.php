<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowRing extends Model
{
    use HasFactory;
    protected $table='follow_ring';

    protected $fillable = [
    
        'uid',
        'fid'

    ];
}
