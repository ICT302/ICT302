<?php

namespace App\Http\Controllers\Auth;

use App;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailRegisteredUser;
use Illuminate\Http\Request;
use File;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function dynamic()
    {        
        return view('auth.register-dynamic');
    }



    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        Mail::to($user->email)->send(new MailRegisteredUser($user));

        session()->flash('success', 'Congratulation! Successfully created your account, please login below!');
        return redirect('login');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    protected function validator(array $data)
    {
        Validator::extend('valid_username', function($attr, $value){
            //check to see if first character is an integer between 1-4
            $first_digit = substr($value, 0, 1);
            if ($first_digit > 0 && $first_digit <= 4)
            {
                return true;
            }
            else
            {
                return false;
            }
        }); 
       
        $message = ["username.digits_between" => "Username must be 6 characters.",
        "username.integer" => "Username can only be integer and without spaces.",
        "username.valid_username" => "First digit can only start from 1 to 4"];

        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'email' => 'required|string|email|max:255|unique:users|confirmed',
            'username' => 'required|integer|digits_between:6,6|valid_username|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], $message);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'dob' => $data['dob'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
            'plain_password' => $data['password'],
        ]);
    }


    public function checkDictionaryList($password)
    {
        //function to check the password against a list of common dictionary word, case insensative.
        $result = false;
        $handle = fopen(public_path('password/dictonarylist-short.txt'), "r");
        if ($handle && !$result) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                $list = trim(preg_replace('/\s\s+/', ' ', $buffer));
                if(strcasecmp($list, $password) == 0){
                    $result = true;
                    break;
                }                
            }   
            fclose($handle);
        }
        return $result;
    }

    public function checkHackerList($password)
    {
        //function to check the password against a list of commonly hacked password
        $result = false;
        $handle = fopen(public_path('password/hackerlist-short.txt'), "r");
        if ($handle && !$result) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                $list = trim(preg_replace('/\s\s+/', ' ', $buffer));
                if(strcmp($list, $password) == 0){
                    $result = true;
                    break;
                }                
            }   
            fclose($handle);
        }
        return $result;
    }

    public function checkPwdEntropy($password)
    {
        $entropy = App::makeWith('entropy',['password' => $password]);
        $attempsPerMin =  App::make('pwdAttemps');

        $result['rating'] = '';
        $result['duration'] = '';
        $result['durationSuggestion'] = '';
        
        //check if passowrd is dictionary word
        if($this->checkDictionaryList($password) || strlen($password) < 4 || $this->checkHackerList($password))
        {
            $result['rating'] = 'Weak';
            //2^number of entropy bits
            $possiblePwds = 2**$entropy['noCheck'];
            $result['duration'] = round($possiblePwds/($attempsPerMin*1440)) .' days'; //per day

            //recommend highest entropy == Dictionary with composition rule
            $possiblePwds = 2**$entropy['dictionaryCompo'];
            $result['durationSuggestion'] = round($possiblePwds/($attempsPerMin*525600)) .' years'; //per year
        }
        else
        {
            //if is not dictionary word, check if have composition rule
            if(count($this->checkPwdComposition($password)) > 0)
            {
                //composition missing certain cretia
                //use dictionary rule
                $result['rating'] = 'Good';
                //2^number of entropy bits
                $possiblePwds = 2**$entropy['dictionary'];
                $result['duration'] = round($possiblePwds/($attempsPerMin*1440)) .' days'; //per day
                if($result['duration'] >= 365)
                {
                    //if max attempt duration more than 365 days, change to year
                    $result['duration'] =  round($possiblePwds/($attempsPerMin*525600),2) .' years'; 
                }

                //recommend highest entropy == Dictionary with composition rule
                $possiblePwds = 2**$entropy['dictionaryCompo'];
                $result['durationSuggestion'] = round($possiblePwds/($attempsPerMin*525600),2) .' years'; //per year                
            }
            else if(count($this->checkPwdComposition($password)) === 0){
                //dictionary and compostion rule
                $result['rating'] = 'Strong';
                $possiblePwds = 2**$entropy['dictionaryCompo'];
                $result['duration'] = round($possiblePwds/($attempsPerMin*525600),2) .' years'; //per year 
                $result['durationSuggestion'] = false; 
            }

        }

        return $result;
    }


    public function checkPwdComposition($password)
    {
        $errors = [];
        //check if has upper case
        if(!preg_match('/[A-Z]/', $password)){
            $errors['pwdUpper'] = true;
        }

        //check if has lower case
        if(!preg_match('/[a-z]/', $password)){
            $errors['pwdLower'] = true;
        }

        //check if has number
        if(!preg_match('/[0-9]/', $password)){
            $errors['pwdNumber'] = true;
        }            

        //check if has special character
        if(!preg_match('/[^\da-zA-Z]/', $password))
        {
            $errors['pwdSpecial'] = true;
        }   

        return $errors;
    }

    //check password
    public function passwordCheck(Request $request)
    {
        $results = [];
        $password = $request->input('password');
        if(strlen($password) >= 8){
            //check for compostion rule
            $results['errors'] = $this->checkPwdComposition($password);

            //check if in hacker list.
            if($this->checkHackerList($password)){
                $results['errors']['pwdHacker'] = true;
            }

            //check if is dictionary word
            if($this->checkDictionaryList($password)){
                $results['errors']['pwdDict'] = true;
            }  

            $results['entropy'] = $this->checkPwdEntropy($password);
        }
        else{
            //password shorter than 8
            $results['errors']['pwdLength'] = true;
        }

        return $results;
    }
}
