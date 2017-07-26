<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    protected $dates = ['datetime'];
    protected $fillable = ['title','content','datetime','user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function isAuthor($user_id)
    {
    	return $user_id == $this->user_id;
    }

}
