<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//report comment on prod
class ChatReport extends Model
{
    use HasFactory;

    protected $table='chat_reports';

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function comment(){
        return $this->belongsTo(CommentOnProd::class);
    }

}
