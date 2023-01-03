<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentOnProd extends Model
{
    use HasFactory;
    
    protected $table='prods_rates';
    protected $fillable = [
        'prod_id',
        'uid',
        'rating',
        'comment',

    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function prod(){
        return $this->belongsTo(Prod::class);
    }
}
