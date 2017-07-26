<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'dob', 'email', 'username', 'plain_password', 'password', 'role'
    ];


    protected $dates = ['dob'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'plain_password', 'remember_token',
    ];

    public function getRoleAttribute($role){
        switch ($role) {
            case 'student':
                return 'Student';
                break;

            case 'admin':
                return 'Administrator';
                break;

            case 'superadmin':
                return 'Super Admin';
                break;
            
            default:
                return 'Student';
                break;
        }
    }

    public function getAllPosts()
    {
        //if is admin, get all posts, if not get own post only
        $posts = $this->posts()->latest()->get();
        if($this->isAdmin())
        {
            $posts = Post::latest()->get();
        }
        return $posts;
    }

    public function getSearchedPost($search_query)
    {
        //if is admin, search all posts, if not search own post only
        //$posts = $this->posts()->where('title', 'like', '%'.$search_query.'%')->orWhere('content', 'like', '%'.$search_query.'%')->with('user')->latest()->get();


        $posts = $this->posts()->where(function($query) use ($search_query){
            $query->where('title', 'like', '%'.$search_query.'%')->orWhere('content', 'like', '%'.$search_query.'%');

        })->latest()->get();   


        if($this->isAdmin())
        {
            $posts = Post::with('user')->where('title', 'like', '%'.$search_query.'%')->orWhere('content', 'like', '%'.$search_query.'%')->latest()->get();
        }

        return $posts;
    }

    public function getCalendarPosts($year, $month)
    {

        $posts = $this->posts()->where(function($query) use ($year, $month){
            $query->whereYear('created_at', '=',$year)->whereMonth('created_at', '=',$month);
        })->latest()->get()->groupBy(function($post) {
            return $post->created_at->day;
        });

        if($this->isAdmin())
        {
            $posts = Post::with('user')->whereYear('created_at', '=',$year)->whereMonth('created_at', '=',$month)->latest()->get()->groupBy(function($post) {
                    return $post->created_at->day;
                });

        }
        return $posts;
    }

    public function getDatePosts($year, $month, $day)
    {

        $posts = $this->posts()->where(function($query) use ($year, $month, $day){
            $query = $query->whereYear('created_at', '=',$year);
            if($month > 0){
               $query = $query->whereMonth('created_at', '=',$month);
            }
            if($day > 0){
                $query = $query->whereDay('created_at', '=',$day);
            }
            
        })->latest()->get();

        if($this->isAdmin())
        {
            $posts = Post::with('user')->whereYear('created_at', '=',$year);
            if($month > 0){
               $posts = $posts->whereMonth('created_at', '=',$month);
            }
            if($day > 0){
                $posts = $posts->whereDay('created_at', '=',$day);
            }
            $posts = $posts->latest()->get();
        }
        return $posts;
    }


    public function isStudent(){
        return $this->role == "Student";
    }

    public function isAdmin(){
        return ($this->role == "Super Admin" || $this->role == "Administrator");
    }

    public function isSuperAdmin(){
        return ($this->role == "Super Admin");
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }
}
