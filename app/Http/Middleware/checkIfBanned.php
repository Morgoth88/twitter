<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class checkIfBanned
{
    /**
     * Handle an incoming request.
     * Check if user has been banned before login
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){

        $user = User::where('email', $request->email)->first();

        if($user->ban == 1){
            $request->session()->flash('status', 'Your account has been banned!');
            return redirect(route('welcome'));
        }
        return $next($request);
    }
}
