<?php

namespace App\Http\Middleware;

use App\Models\School;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CheckLicense
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
        if($user->roles){
            foreach ($user->roles as $role){
                if($role->role_name == "Teacher" || $role->role_name == "School Admin"){
                    $school = School::where('id', $user->school_id)->first();
                    if($school!=null)
                    {
                        if( $school->license_to==null ){
                            return redirect('/xadmin/school_license/license_expired');
                        }
                        if($school->license_to < Carbon::now() && $school->license_to!=null ){
                            return redirect('/xadmin/school_license/license_expired');
                        }
                    }
                    if($school==null)
                    {
                        return redirect('/xadmin/school_license/license_expired');
                    }

                }
            }
        }
        return $next($request);
    }
}
