<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
	public function __construct()
    {
    	$this->middleware('auth');
    	$this->middleware('author')->only(['edit', 'update']);
        $this->middleware('student')->except(['index', 'show', 'search','calendar','datePosts']);
 
    }

    public function index(){

		$posts = Auth::user()->getAllPosts();
		return view('post.index', compact('posts'));		
	}

	public function create(){
		return view('post.create');
	}

	public function store(Request $request){
		
		//Requirements for the attributes of the post
		$rules = ['title' => 'required',
					'content' => 'required'];
		
		//validate the post
		$this->validate($request, $rules);

		//create new post
		$new_post = Post::create([
			'title' => $request->input('title'),
			'content' => $request->input('content'),
			'datetime' => Carbon::now(),
			'user_id' => Auth::user()->id
			]);

		//show sucess message
		session()->flash('success', 'Congratulation! Successfully created new post!');
		return redirect('post');
	}

	public function show(Post $post)
	{

    	if(!Auth::user()->isAdmin() && !$post->isAuthor(Auth::id()))
		{
            session()->flash('error', 'You are not the author!');
            return redirect('post');
    	}
		return view('post.show', compact('post'));
	}

	public function edit(Post $post)
	{		
		if(!$post->isAuthor(Auth::id())){
			session()->flash('error', 'You are not the author!');
			return redirect('post');
		}

		return view('post.edit', compact('post'));
	}

	public function update(Request $request, Post $post)
	{
		$post->update([
			'title' => $request->input('title'),
			'content' => $request->input('content'),
			]);

		session()->flash('success', 'Congratulation! Successfully updated post!');
		return redirect('post');
	}

	public function destroy(Post $post){

		$post->delete();
		session()->flash('success', 'Congratulation! Successfully deleted new post!');
		return redirect('post');	
	}

	public function search(Request $request)
	{
		$search_query = $request->input('search');
		$searched_posts = Auth::user()->getSearchedPost($search_query);
		return view('post.search', compact('searched_posts', 'search_query'));
	}

    public function calendar($year, $month, Request $request){
    	$user = Auth::user();
    	if($request->input('user_id'))
    	{
    		$user = User::find($request->input('user_id'));
    	}

		$all_events = [];
		$calendar_posts = $user->getCalendarPosts($year,$month);
		foreach ($calendar_posts as $day => $posts) {
			$event = ["allDay" => true,
		        "title" => count($posts)." posts",
		        "start" => $year."-".$month."-".$day." 00:00:00"
		    ];
    		array_push($all_events, $event);
		}
		return $all_events;
	}


    public function datePosts($year, $month  = false, $day = false){

		$date_posts = Auth::user()->getDatePosts($year, $month, $day);
		return view('post.date', compact('date_posts', 'year', 'month', 'day'));
	}
}
