<?php

use Illuminate\Database\Seeder;
use App\User;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create the default super admin
		User::create(['first_name' => 'Super', 'last_name' => 'Admin', 'dob' => Carbon::now(), 'email' => 'journaltouch@gmail.com', 'username' => 'superadmin', 'password' => bcrypt('password'), 'plain_password' => 'password', 'role' => 'superadmin']);

    }
}
