<?php

namespace App\Http\Middleware;

use App\Models\School;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class checkActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $user = Auth::user();
        if($user->state==0)
        {
            return redirect('/xadmin/userDeactive/active');
        }
        if($user->state==1)
        {
            return $next($request);

        }
    }
}
