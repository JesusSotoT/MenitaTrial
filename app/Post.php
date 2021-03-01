<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';


    protected $fillable = [
        'userId', 'title', 'body', 'is_published', 'created_at', 'updated_at'
    ];
}
