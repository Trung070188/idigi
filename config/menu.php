<?php
return [
    [
        "name" => "Dashboard",
        "icon" => "fa fa-home",
        "url" => "/xadmin/dashboard/index",
        "group" => 1
    ],
    [
        "name" => "Inventories",
        "icon" => "fa fa-archive",
        "group" => 1,
        'url' => '/xadmin/inventories/index',
    ],
    [
        "name" => "Lesson",
        "icon" => "fa fa-book-open",
        'url' => '/xadmin/lessons/index',
    ],

     [  "name" => "Users, Roles",
        "icon" => "fa fa-users",
        "group" => 1,
        'base' => '/xadmin/users',
        'subs' => [
            [
                "name" => "User",
                "icon" => "fa fa-plus",
                "url" => "/xadmin/users/index",
                "group" => 1,
            ],
            [
                "name" => "Role",
                "icon" => "fa fa-plus",
                "url" => "/xadmin/roles/index",
                "group" => 1,
            ],
        ],
         ],


    [
        "name" => "School",
        "icon" => "fa fa-school",
        'url' => '/xadmin/schools/index',
    ],

    [
        "name" => "System",
        "icon" => "fa fa-users",
        "group" => 1,
        'base' => '/xadmin/system',
        'subs' => [
            [
                "name" => "File Manager",
                "icon" => "fa fa-files-o",
                "url" => "/xadmin/elfinder",
                "group" => 1,
            ],

        ]
    ],
    [
        "name" => "Manage devices",
        "icon" => "fa fa-school",
        'url' => '/xadmin/user_devices',
        'subs'=>[
            [
                "name" => "Manage devices",
                "icon" => "fa fa-plus",
                "url" => "/xadmin/user_devices/index",
                "group" => 1,
            ],
            [
                "name" => " Approval devices",
                "icon" => "fa fa-plus",
                "url" => "/xadmin/user_devices/approval",
                "group" => 1,
            ],
        ]

    ],
];
