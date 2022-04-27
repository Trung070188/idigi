<?php
return [
    [
        "name" => "Dashboard",
        "icon" => "fa fa-home",
        "url" => "/xadmin/dashboard/index",
        "group" => 1
    ],
    [
        "name" => "Quản trị viên",
        "icon" => "fa fa-users",
        "group" => 1,
        'base' => '/xadmin/users',
        'subs' => [
            [
                "name" => "Danh sách",
                "icon" => "fa fa-plus",
                "url" => "/xadmin/users/index",
                "group" => 1,
            ],
            [
                "name" => "Thêm SP",
                "icon" => "fa fa-plus",
                "url" => "/xadmin/users/create",
                "group" => 1,
            ]
        ]
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
        "group" => 1,
        'url' => '/xadmin/lessons/index',
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
        "name" => "Logout",
        "icon" => "fa fa-sign-out-alt",
        "url" => "/xadmin/logout",
        "group" => 1
    ],
];
