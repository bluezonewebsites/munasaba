<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppHelp extends Model
{
    use HasFactory;
    protected $table='help';
    protected $fillable = [
    'about' ,
    'about_en' ,
    'conds',
    'conds_en' ,
    'fb',
    'insta',
    'phone',
    'email',
    'tw',
    ];
}
