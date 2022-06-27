<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckInventories
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user=Auth::user();
        foreach ($user->roles as $role)
            if($role->role_name=='Super Administrator'|| $role->role_name=='Moderator')
            {
                return $next($request);
            }
        else{
            return redirect('/xadmin/dashboard/index');
        }
    }
}
