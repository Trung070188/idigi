<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Group extends BaseModel
{
    const SUPER_USER = 1;
    const NORMAL_USER = 2;
    const EDITOR = 3;

    public function getAllowedRoutes()
    {
        $routes = $this->routes;

        $routes = explode("\n", $routes);
        $routes = array_map('trim', $routes);
        $allowed = [];

        foreach ($routes as $route) {
            if (!$route) {
                continue;
            }

            if ($route[0] !== '#') {
                $allowed[] = $route;
            }
        }
        return $allowed;
    }
}
