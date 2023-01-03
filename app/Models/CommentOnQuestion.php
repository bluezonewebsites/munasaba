<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentOnQuestion extends Model
{
    use HasFactory;
    protected $table='comment_on_questions';

    
    protected $fillable = [
        'quest_id',
        'uid',
        'mention',
        'comment',

    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function question(){
        return $this->belongsTo(Question::class);
    }
}
