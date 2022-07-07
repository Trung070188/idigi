<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
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
        $roles = $user->roles;

        $isAdmin = 0;
        $roleIds = [];
        foreach ($roles as $role){
            $roleIds[] = $role->id;
            if($role->role_admin == "Super Administrator"){
                $isAdmin = 1;
            }
        }

        if($isAdmin == 1){
            return $next($request);
        }else{
            $parse = parse_url($request->url);
            $rolePermissionCount = \App\Models\RoleHasPermission::whereIn('role_id', $roleIds)
                ->whereHas('permission',function($q) use ($parse){
                    $q->where('path',$parse['path']);
                })
                ->count();

            if($rolePermissionCount > 0){
                return $next($request);
            }
            $permissionCount = Permission::where('path', $parse['path'])->count();

            if($permissionCount == 0){
                return $next($request);
            }
        }

        return redirect('/xadmin/dashboard');
    }
}
