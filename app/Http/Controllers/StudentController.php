<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(){
    	$students = User::where('role', 'student')->latest()->get();
    	return view("student.index", compact(["students"]));
    }

    public function studentPost(User $student)
    {
    	if(!$student->isStudent())
    	{
			session()->flash('error', 'User selected is not student and does not have any post.');
			return redirect('student');
    	}

    	if($student->posts->count() == 0)
    	{
    		session()->flash('error', 'Student does not have any post.');
    		return redirect ('student');
    	}

    	return view('student.post', compact('student'));
    }

    public function searchPost(User $student, Request $request)
    {
        $search_query = $request->input('search');
        $searched_posts = $student->getSearchedPost($search_query);

        return view('student.post-search', compact('searched_posts', 'search_query', 'student'));
    }
}
