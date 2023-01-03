<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeOnProd extends Model
{
    use HasFactory;
    protected $table='likes_on_rates';

    protected $fillable = [
        'comment_id',
        'uid',
        'like_type',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function prod_comment(){
        return $this->belongsTo(CommentOnProd::class);
    }
}

