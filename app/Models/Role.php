<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends BaseModel
{
    protected $table = 'roles';
    protected $autoSchema = false;
    const SUPER_USER = 1;
    const NORMAL_USER = 2;
    const EDITOR = 3;
    protected $fillable = [
        'role_name',
        'role_description'
    ];

//    public function getAllowedRoutes()
//    {
//        $routes = $this->routes;
//
//        $routes = explode("\n", $routes);
//        $routes = array_map('trim', $routes);
//        $allowed = [];
//
//        foreach ($routes as $route) {
//            if (!$route) {
//                continue;
//            }
//
//            if ($route[0] !== '#') {
//                $allowed[] = $route;
//            }
//        }
//        return $allowed;
//    }
}
