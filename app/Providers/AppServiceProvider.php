<?php

namespace App\Providers;

use App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        App::singleton('pwdAttemps', function($app)
        {
            //# of attacker Password Guesses per Minute
            return 184;
        });
        
        App::singleton('entropy', function($app, $param)
        {
            //get bits of entropy based on password length
            //Includes many assumptions, including that most people do not use truly random passwords. Uses password entrophy calculations* to extrapolate "likely" passwords

            //use NIST entropy model
            //http://nvlpubs.nist.gov/nistpubs/SpecialPublications/NIST.SP.800-63-2.pdf
            //Table A.1
            //94 Character Alphabet
            //Based on No Checks/Dictionary Rule/Dict. & Composition Rule

            $password = $param['password'];
            //$password = '1234567890123456789012345678901234567890';

            //NO CHECK ENTROPY
            $result['noCheck'] = 4;
            //Dictionary Rule Entropy
            $result['dictionary'] = 0;
            //Dictionary & Composition Rule Entropy
            $result['dictionaryCompo'] = 0;

            for ($i=0; $i < strlen($password); $i++) { 
                if($i > 0 && $i < 8){
                    //add 2 bits to no check entropy
                    $result['noCheck'] += 2;

                    
                    if($i == 3){
                        //if password length is 4, then give dictonary entropy a base value of 14
                        $result['dictionary'] = 14;

                        //if password length is 4, then give dictonary entropy a base value of 16
                        $result['dictionaryCompo'] = 16;
                    }
                    //if password length is 5-6, then give add 3 bits
                    if($i > 3 && $i< 6) 
                    {
                        $result['dictionary'] += 3; 
                        $result['dictionaryCompo'] = $result['dictionary'] + 3;   
                    }
                    //if password length is 7-8, then give add 2 bits
                    else if($i >= 6 && $i < 8)
                    {
                        $result['dictionary'] += 2;
                        if($i == 6){
                            $result['dictionaryCompo'] = $result['dictionary'] + 5;       
                        }
                        else if($i == 7){
                            $result['dictionaryCompo'] = $result['dictionary'] + 6;          
                        }                        
                    }

                }
                else if($i >= 8 && $i < 20){
                    //add 1.5 bits to no check entropy
                    $result['noCheck'] += 1.5;
                    //add 1 bit to dictionary entropy
                    $result['dictionary'] += 1;

                    $result['dictionaryCompo'] = $result['dictionary'] + 6;   
                    

                }
                else if($i >= 20)
                {
                    //add 1 bit to no check entropy
                    $result['noCheck'] += 1;
                    //add 1 bit to dictionary entropy
                    $result['dictionary'] += 1;

                    $result['dictionaryCompo'] = $result['dictionary'] + 6;   
                }
            }

            return $result;
        });

    
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
