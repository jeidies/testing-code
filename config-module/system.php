<?php
/**
 * Created by PhpStorm
 * Date: 21/10/2016
 * Time: 8:38
 * Author: Daniel Simangunsong
 *
 * Note.
 *
 */

return [
        'tables' => [
                'permissions', 'configurations', 'admins', 'admin_roles', 'roles', 'menus'
        ],
        'panels' => [
                'dashboard' => [
// When not set, will use system determination which is return helper/listing/index/systems/admins
// True will return systems/admins/listing
                        'permalink' => true,
                        'class' => 'dashboard',
                        'label' => 'Dashboard'
                ],
                'admins' => [
                        'permalink' => true,
// When not set, will translate group name
                        'label' => 'Admins',
// Panel allowed action
                        'actions' => [
                                'activeness',
                                'create' => [
// Permission should be an array, but its okay to set it as string when you have just one item
// By default this permission will check with OR operator, mean when admin have any one of these,
// then it will be passed in validator manager. Assign true in to last item, to force admin
// having all permissions
                                        'permissions' => [
                                                'is_system', 'activeness'
                                        ],
// Actions rules if any. This will be checking on routes while possible, or on admin base controller, or on
// the related controller it self
                                        'rules' => [
                                        ],
// Transaction form if any
                                        'form' => [
                                                'title' => 'someBody',
// Following by input key => attributes
// Input key should be following "moduleName.tableName.columnName" format name
                                                'system.admins.username' => [
                                                        'type' => 'text',
                                                        'length' => '100',
// Input rules:

                                                        'rules' => [

                                                        ]
                                                ],
                                                'system.admins.password',
                                                'system.admins.email' => [
                                                        'class' => 'email'
                                                ],
                                                'system.admin_roles.role_id' => [
                                                        'label' => 'Role',
                                                        'type' => 'select',
                                                        'multiple',
                                                        'options' => ['doe' => [
                                                                1 => 'Superadmin',
                                                                2 => 'Administrator']
                                                        ],
                                                        'info' => 'put some information here'
                                                ]
                                        ]
                                ],
                                'edit',
                                'delete',
                                'is_system'
                        ]
                ],
                'roles'
        ],
];