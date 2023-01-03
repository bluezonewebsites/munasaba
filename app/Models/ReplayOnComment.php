<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// replay on comment on question
class ReplayOnComment extends Model
{
    use HasFactory;
    protected $table='comment_on_rates';

    protected $fillable = [
        'comment_id',
        'uid',
        'mention',
        'comment',

    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function comment(){
        return $this->belongsTo(CommentOnQuestion::class);
    }
}
