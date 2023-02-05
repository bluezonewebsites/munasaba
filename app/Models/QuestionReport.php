<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionReport extends Model
{
    use HasFactory,SoftDeletes;

    protected $table='questions_reports';
    protected $guarded = [];


}
