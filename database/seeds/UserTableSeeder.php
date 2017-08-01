<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
class UserTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		//delete users table records
		DB::table('user')->delete();
		DB::table('user')->truncate();
		//insert some dummy records

		User::create(array(
		   'username' => 'amo',
		   'password' => Hash::make('12345678'),
		   'accesslevel' =>'0',
			 'firstname' => 'John',
			 'middlename' => '',
			 'lastname' => 'Doe',
			 'email' => 'john@yahoo.com',
		   'status' =>'1'
		));

		User::create(array(
		   'username' => 'accounting',
		   'password' => Hash::make('12345678'),
		   'accesslevel' =>'1',
			 'firstname' => 'Juan',
			 'middlename' => '',
			 'lastname' => 'Dela Cruz',
			 'email' => 'juandelacruz@yahoo.com',
		   'status' =>'1'
		));

		User::create(array(
		   'username' => 'admin',
		   'password' => Hash::make('12345678'),
		   'accesslevel' =>'2',
			 'firstname' => 'Peter',
			 'middlename' => '',
			 'lastname' => 'Pandecoco',
			 'email' => 'pedropandesal@yahoo.com',
		   'status' =>'1'
		));
	}



}
