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
                'configurations', 'admins', 'admin_roles', 'roles', 'menus'
        ],
        'panel-menus' => [
                'roles',
                'some-group' => [
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
// Input key must following "moduleName.tableName.columnName" convention name
                                                'some-module.some-table.some-column' => [
                                                        'type' => 'text',
                                                        'length' => '100',
// Input rules:

                                                        'rules' => [

                                                        ]
                                                ]
                                        ]
                                ],
                                'edit',
                                'delete',
                                'is_system'
                        ]
                ]
        ],
];