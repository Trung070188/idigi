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
         'path' => '/roles/{action}',
         'action' => 'RolesController',
         'name' => 'roles',
     ],
    [
        'path' => '/elfinder/{action}',
        'action' => 'ElfinderController',
        'name' => 'elfinder',
    ],
    [
        'path' => '/inventories/{action}',
        'action' => 'InventoriesController',
        'name' => 'inventories',
    ],
];
