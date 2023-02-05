<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReplyReport extends Model
{
    use HasFactory,SoftDeletes;

    protected $table='reply_reports';
    protected $guarded = [];


}
