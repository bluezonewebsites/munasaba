<?php

namespace App\Models;

use Carbon\Carbon;
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
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function getImageAttribute()
    {
        return asset('/image/' . $this->img);
    }
}
