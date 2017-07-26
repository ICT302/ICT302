<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailRegisteredAdmin;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('superadmin')->only('edit');
    }

    public function index(){
    	$admins = User::where('role', "!=", 'student')->latest()->get();
    	return view("admin.index", compact(["admins"]));
    }

    public function create(){
    	
    	return view("admin.create");
    }

    public function store(Request $request){


    	Validator::make($request->input(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'email' => 'required|string|email|max:255|unique:users|confirmed',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ])->validate();
    	
    	$admin = User::create(['first_name' => $request->input('first_name'),
    		'last_name' => $request->input('last_name'),
    		'dob' => $request->input('dob'),
    		'email' => $request->input('email'),
    		'username' => $request->input('username'),
    		'password' => bcrypt($request->input('password')),
    		'plain_password' => $request->input('password'),
    		'role' => 'admin']);

    	Mail::to($admin->email)->send(new MailRegisteredAdmin($admin));

    	session()->flash('success', 'Congratulation! Successfully created a new '.$admin->role.'!');
		return redirect('admin');
    }

    public function edit(User $admin){

    	return view('admin.edit', compact('admin'));
    }

    public function update(User $admin, Request $request){
		

    	Validator::make($request->input(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'email' => 'required|string|email|max:255|unique:users,email,'.$admin->id,
            'username' => 'required|string|unique:users,username,'.$admin->id
        ])->validate();
    	
    	$admin->update(['first_name' => $request->input('first_name'),
    		'last_name' => $request->input('last_name'),
    		'dob' => $request->input('dob'),
    		'email' => $request->input('email'),
    		'username' => $request->input('username')]);   

    	session()->flash('success', 'Congratulation! Successfully updated admin!');
		return redirect('admin');
    }

    public function destroy(User $admin)
    {

        if($admin->isSuperAdmin())
        {
            $delete['result'] = "false";
            $delete['message'] = "Cannot delete super admin.";
        }
        else
        {
            $delete = [];
            $result = $admin->delete();

            if($result)
            {
                //delete success
                $delete['result'] = "true";
                $delete['message'] = "Successfully deleted admin.";
            }
            else{
                $delete['result'] = "false";
                $delete['message'] = "Error in deleting admin. Please try later";
            }
        }


        return $delete;
    }
}
