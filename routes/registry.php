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
        'path' => '/inventories/{action}',
        'action' => 'InventoriesController',
        'name' => 'inventories',
    ],
    [
        'path' => '/lessons/{action}',
        'action' => 'LessonsController',
        'name' => 'lessons',
    ],
    [
        'path' => '/schools/{action}',
        'action' => 'SchoolsController',
        'name' => 'schools',
    ],
];