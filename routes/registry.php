<?php
 return
 [
    [
        'path' => '/options/{action}',
        'action' => 'OptionsController',
        'name' => 'options',
    ],
    [
        'path' => '/users/{action}',
        'action' => 'UsersController',
        'name' => 'users',
    ],
    [
        'path' => '/products/{action}',
        'action' => 'ProductsController',
        'name' => 'products',
    ],
    [
        'path' => '/permissions/{action}',
        'action' => 'PermissionsController',
        'name' => 'permissions',
    ],
    [
        'path' => '/elfinder/{action}',
        'action' => 'ElfinderController',
        'name' => 'elfinder',
    ],
    [
        'path' => '/roles/{action}',
        'action' => 'RolesController',
        'name' => 'roles',
    ],
];