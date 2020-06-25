<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		User::insert([
			[
				'name' => 'John Doe',
				'email' => 'admin@example.com',
				'role' => 'admin',
				'email_verified_at' => now(),
				'password' => Hash::make('abcd1234'),
				'created_at' => now(),
				'updated_at' => now(),
			],
			[
				'name' => 'Jane Doe',
				'email' => 'employee@example.com',
				'role' => 'employee', // default set to employee if role not passed
				'email_verified_at' => now(),
				'password' => Hash::make('abcd1234'),
				'created_at' => now(),
				'updated_at' => now(),
			],
		]);
	}
}
