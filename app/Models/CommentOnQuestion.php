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
    public function toArray()
    {
        $data['id'] = $this->id;
        $data['uid'] = $this->uid;
        $data['quest_id'] = $this->quest_id;
        $data['comment'] = $this->comment;
        $data['mention'] = $this->mention;
        $data['created_at'] = $this->created_at;
        $data['updated_at'] = $this->updated_at;
        $data['comment_user_name'] = $this->user->name;
        $data['comment_user_last_name'] = $this->user->last_name;
        $data['comment_user_verified'] = $this->user->verified;
        $data['comment_user_pic'] = $this->user->pic;
        $data['is_like'] = $this->is_like;
        $data['count_like'] = $this->likes->count();
        return $data;
    }
    public function getIsLikeAttribute()
    {
        if(Auth()->check()){

            return $this->likes->where('uid',Auth()->id())->count() > 0 ? 1 : 0;
        }

        return 0;
    }

    public function user(){
        return $this->belongsTo(User::class,'uid')->withDefault([
            'id'=>0,
            'name'=>'',
            'last_name'=>'',
            'verified'=>0,
            'pic'=>'',
        ]);
    }
    public function question(){
        return $this->belongsTo(Question::class,'quest_id','id');
    }
    public function likes(){
        return $this->hasMany(LikeOnQuest::class,'comment_id','id')
            ->where('like_type',0);
    }
}
