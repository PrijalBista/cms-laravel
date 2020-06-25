<?php

namespace App\Http\Controllers\Api\CMS\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return User::all();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$validatedData = $request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|unique:users,email',
			'role' => 'required|in:employee,admin',
			'password' => 'required|string|min:6',
		]);

		$validatedData['password'] = Hash::make($validatedData['password']);

		User::create($validatedData);

		return response()->json(null, 200);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, User $user)
	{
		$validatedData = $request->validate([
			'name' => 'required|string|max:255',
			'role' => 'required|in:employee,admin',
			'password' => 'required|string|min:6|confirmed',
		]);

		$user->update($validatedData);
		return response()->json(null, 200);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request,User $user)
	{
		// stop deleting self account
		$appUser = $request->user();
		if($appUser && $appUser->email === $user->email) {
			return response()->json(['message' => 'Unauthorized'], 401);
		}

		$user->delete();
		return response()->json(null, 200);
	}


	//  LOGIN - LOGOUT

	public function login(Request $request) {

		$validatedData = $request->validate([
			'email' => 'required|email',
			'password' => 'required'
		]);

		$appUser = User::where('email', $validatedData['email'])->first();

		if(!$appUser || !Hash::check($validatedData['password'], $appUser->password)) {

			throw ValidationException::withMessages([
				'auth_fail' => ['Invalid Username or Password !'],
			]);
		}

		return [
			'accessToken' => $appUser->createToken($appUser->role)->plainTextToken,
			'userName' => $appUser->name,
			'userEmail' => $appUser->email,
			'userRole' => $appUser->role,
			'userEmailVerifiedAt' => $appUser->email_verified_at,
		];
	}

	public function logout(Request $request) {
		$request->user()->tokens()->delete();
		return response()->json(null, 200);
		// return response()->json('logout', 201);
	}
}
