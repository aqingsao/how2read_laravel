<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;
use Log;

class SimpleAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::check()) {
            $user = new User;
            $user->name=uniqid();
            $user->save();
            Log::info($request->path().', user not in session, create user '. $user->id);
            Auth::login($user, True);
        }
        else{
            Log::debug($request->path().', user: '. Auth::id());
        }

        return $next($request);
    }
}
