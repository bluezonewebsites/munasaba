<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdImage extends Model
{

    use HasFactory,SoftDeletes;
    
    protected $table='prod_imgs';

    protected $fillable = [
        'prod_id',
        'mtype',
        'img',
    ];
    protected $appends=[
        'image',
    ];
    public function getImageAttribute()
    {
        return asset('/image/' . $this->img);
    }
}
