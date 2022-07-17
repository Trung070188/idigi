<?php
return [
//    [
//        "name" => "Dashboard",
//        "icon" => "fa fa-home",
//        "url" => "/xadmin/dashboard/index",
//        "group" => 1,
//        "roles" => ['Super Administrator']
//    ],
    [
        "name" => "Inventories",
        "icon" => "fa fa-archive",
        "group" => 1,
        'url' => '/xadmin/inventories/index',
        "roles" => ['Super Administrator','Moderator']
    ],
    [
        "name" => "Lessons",
        "icon" => "fa fa-book-open",
        'url' => '/xadmin/lessons/index',
        "roles" => ['Super Administrator','Moderator','Teacher','Administrator']
    ],

    ["name" => "Users, Roles",
        "icon" => "fa fa-users",
        "group" => 1,
        'base' => '/xadmin/users',
        "roles" => ['Super Administrator'],
        'subs' => [
            [
                "name" => "Manage User",
                "icon" => "fa fa-plus",
                "url" => "/xadmin/users/index",
                "group" => 1,
                "roles" => ['Super Administrator']
            ],
            [
                "name" => "Manage Role",
                "icon" => "fa fa-plus",
                "url" => "/xadmin/roles/index",
                "group" => 1,
                "roles" => ['Super Administrator']
            ],
        ],
    ],
    [

        "name" => "Teacher",
        "icon" => "	fas fa-landmark",
        "group" => 1,
        "url" => "/xadmin/users/teacher",
        "roles" => ['Super Administrator','Administrator']

    ],


    [
        "name" => "School",
        "icon" => "fa fa-school",
        'url' => '/xadmin/schools/index',
        "roles" => ['Super Administrator','Administrator']
    ],

//    [
//        "name" => "System",
//        "icon" => "fa fa-users",
//        "group" => 1,
//        'base' => '/xadmin/system',
//        "roles" => ['Super Administrator','Administrator'],
//        'subs' => [
//            [
//                "name" => "File Manager",
//                "icon" => "fa fa-files-o",
//                "url" => "/xadmin/elfinder",
//                "group" => 1,
//                "roles" => ['Super Administrator','Administrator']
//            ],
//
//        ]
//    ],
    [
        "name" => "Manage devices",
        "icon" => "	fas fa-laptop",
        'url' => '/xadmin/user_devices/index',
        'group' => 1,
        "roles" => ['Super Administrator','Administrator','Teacher','Moderator']
    ],
    [
        "name" => "Notification",
        "icon" => "fa fa-bell",
        "group" => 1,
        'url' => '/xadmin/notifications/index',
        "roles" => ['Super Administrator']
    ],

    [
        "name" => "Download Application",
        "icon" => "fa fa-download",
        "group" => 1,
        'url' => '/xadmin/app_versions/index',
        "roles" => ['Super Administrator','Administrator','Teacher','Moderator']

    ],
];
