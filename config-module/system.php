<?php
/**
 * Created by PhpStorm
 * Date: 21/10/2016
 * Time: 8:38
 * Author: Daniel Simangunsong
 *
 */

return [
        'tables' => [
                'permissions', 'configurations', 'admins', 'admin_roles', 'roles', 'menus'
        ],
        'panels' => [
                'roles',
                'admins' => [
                        'listing' => [
// When not set, will use system determination
                                'permalink' => 'some-link',
// When not set, will translate group name
                                'label' => 'Dashboard',
                        ],
// Panel allowed action
                        'actions' => [
                                'activeness',
                                'create' => [
// Transaction rules if any. This will be checking on routes while possible, or on admin base controller, or on
// the related controller it self
                                        'rules' => [
                                                'permissions' => [
                                                        'is_system', 'activeness'
                                                ]
                                        ],
// Transaction form if any
                                        'form' => [
// Following by input key => attributes
// Input key should be following "moduleName.tableName.columnName" format name
                                                'system.admins.username' => [
                                                        'type' => 'text',
                                                        'length' => '100',
// Input rules:

                                                        'rules' => [

                                                        ]
                                                ],
                                                'system.admins.password' => [

                                                ],
                                                'system.admin_roles.role_id'
                                        ]
                                ],
                                'edit',
                                'delete',
                                'is_system'
                        ]
                ]
        ],
];