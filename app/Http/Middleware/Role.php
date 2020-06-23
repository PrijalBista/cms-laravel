<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * Handle an incoming request Check if user is authenticated and has at least one role from roles passed when using middleware
     * Usuage: Route::middleware('role:admin') only user with role admin can get in
     * Usuage: Route::middleware('role:admin,employee') both admin or employee can get in
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $appUser = $request->user();

        if(!$appUser) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        foreach ($roles as $role) {
            if($appUser->role === $role){
                return $next($request);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
