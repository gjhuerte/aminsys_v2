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
			 'firstname' => 'Assets',
			 'middlename' => 'Management',
			 'lastname' => 'Office',
			 'email' => 'amo@yahoo.com',
		   'status' =>'1'
		));

		User::create(array(
		   'username' => 'accounting',
		   'password' => Hash::make('12345678'),
		   'accesslevel' =>'1',
			 'firstname' => 'Accounting',
			 'middlename' => '',
			 'lastname' => 'Office',
			 'email' => 'accountingoffice@yahoo.com',
		   'status' =>'1'
		));

		User::create(array(
		   'username' => 'admin',
		   'password' => Hash::make('12345678'),
		   'accesslevel' =>'2',
			 'firstname' => 'Administrator',
			 'middlename' => '',
			 'lastname' => 'Only',
			 'email' => 'pedropandesal@yahoo.com',
		   'status' =>'1'
		));
	}



}
