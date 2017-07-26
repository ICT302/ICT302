<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;

class ChangePassWordController extends Controller
{
    public function edit(User $user)
    {
    	return view('auth.passwords.changePassword', compact('user'));
    }

     public function update(Request $request){

        Validator::extend('valid_password', function($attr, $value){
            
            if ($value == Auth::User()->plain_password)
            {
                return true;
            }
            else
            {
                return false;
            }
        }); 
       
        $message = ["current_password.valid_password" => "Incorrect current password."];
		
		Validator::make($request->input(), [
			'current_password' => 'required|string|valid_password',
            'password' => 'required|string|min:8|confirmed'
        ], $message)->validate();
    	
    	Auth::user()->update(['plain_password' => $request->input('password'),
    		'password' => bcrypt($request->input('password'))]);
    		
    	session()->flash('success', 'Congratulation! Successfully in updating the password!');
		return redirect('/');
    }
}
