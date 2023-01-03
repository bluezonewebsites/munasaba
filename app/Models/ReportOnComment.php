<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//report comment on prod
class ReportOnComment extends Model
{
    use HasFactory;
    
    protected $table='comment_reports';

    protected $fillable = [
        'comment_id',
        'uid',
        'reson'

    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function comment(){
        return $this->belongsTo(CommentOnProd::class);
    }

}
