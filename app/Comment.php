<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';


    protected $fillable = [
        'userId', 'title', 'comment_body','comment_status','created_at', 'updated_at'
    ];
}
