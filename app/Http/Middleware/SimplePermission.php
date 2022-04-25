<?php

namespace App\Http\Middleware;

use App\Exceptions\PermissionException;
use App\Models\Permission;
use App\Models\User;
use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\UnauthorizedException;

class SimplePermission extends Middleware
{

    public function handle($request, Closure $next)
    {
        $action = $request->route('action');
        $class = Route::current()->getAction('controller');

        if (!$action) {
            throw new \Exception("Route define must have {action}");
        }

        if (!$class) {
            throw new \Exception("Route define must have a class");
        }

        $p = Permission::where('class', $class)
            ->where('action', $action)
            ->first();
       // dd($p->toArray());


        return $next($request);
    }

    /**
     * Convert allowed route to regex patterns
     * @param array $allowed
     * @return string
     */
    private static function getPatterns(array $allowed)
    {
        $p = '/' . implode('|', array_map(function ($a) {
                $a = preg_quote($a, '/');
                $a = str_replace('*', '.*', $a);
                return $a;
            }, $allowed)) . '/';

        return $p;
    }

}
