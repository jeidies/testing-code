<?php
/**
 * Created by PhpStorm.
 * User: DanielSimangunsong
 * Date: 12/15/2016
 * Time: 3:23 PM
 */
//redAlderGreatDane
//$arr = [
//        'name' => 'DBA',
//        'email' => 'zoidsimangunsong@gmail.com',
//        'reminder' => 'polygon',
//        'level' => 0
//];

return [
        'configurations' => [
                [
                        'id' => 1,
                        'key' => '48d35125f4a3c2c005d5b0697463c4651704b427',
                        'setting' => '318c16c276f9a80cef60793f9f18e51407bb087c'
                ]
        ],
        'roles' => [
                [
                        'id' => 1,
                        'title' => 'superadmin',
                        'role_level' => 10,
                        'is_system' => 1,
                        'is_admin' => 1,
                        'is_active' => 1
                ],
                [
                        'id' => 2,
                        'title' => 'administrator',
                        'role_level' => 20,
                        'is_admin' => 1,
                        'is_active' => 1
                ],
                [
                        'id' => 3,
                        'title' => 'support',
                        'role_level' => 30,
                        'is_admin' => 1,
                        'is_active' => 1
                ],
                [
                        'id' => 4,
                        'title' => 'visitor',
                        'role_level' => 999,
                        'is_active' => 1
                ]
        ],
        'admins' => [
                [
                        'id' => 1,
                        'username' => 'superadmin',
                        'password' => Hash::make('superadmin'),
                        'email' => 'su@webmail.com', 'is_system' => 0,
                        'is_system' => 1
                ],
                [
                        'id' => 2, 'username' => 'administrator',
                        'password' => Hash::make('administrator'),
                        'email' => 'ad@webmail.com',
                        'is_system' => 1
                ],
                [
                        'id' => 3,
                        'username' => 'support',
                        'password' => Hash::make('support'),
                        'email' => 'sr@webmail.com',
                        'is_system' => 1
                ]
        ],
        'admin_roles' => [
                ['admin_id' => 1, 'role_id' => 1],
                ['admin_id' => 2, 'role_id' => 2],
                ['admin_id' => 3, 'role_id' => 3]
        ]
];