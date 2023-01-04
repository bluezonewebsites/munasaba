<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeOnQuest extends Model
{
    use HasFactory;
    // like on comments on Quest table
    protected $table='like_on_replay';

    protected $fillable = [
        'comment_id',
        'uid',
        'like_type',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function quest_comment(){
        return $this->belongsTo(CommentOnQuestion::class);
    }

}
