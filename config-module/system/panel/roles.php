<?php
/**
 * Created by PhpStorm.
 * User: DanielSimangunsong
 * Date: 11/29/2016
 * Time: 12:57 PM
 */

return [
        'permalink' => true,
// When not set, will translate group name
// Panel allowed action
        'actions' => [
                'activeness',
                'create' => [
// Transaction form if any
                        'form' => [
                                'title' => 'Create Role',
// Following by input key => attributes
// Input key should be following "moduleName.tableName.columnName" format name
                                'system.roles.role_level' => [
                                        'type' => 'text',
                                        'label' => 'Level',
                                        'rules' => 'max:255|min:10'
                                ],
                                'system.roles.title',
                                'system.roles.is_admin',
                                'system.roles.is_active' => [
// Mean current login admin must have activeness permission
                                        'permissions' => 'activeness',
                                ],
                                'system.roles.is_system'
                        ]
                ],
                'edit' => [
// Actions rules if any. This will be checking on routes while possible, or on admin base controller, or on
// the related controller it self
                        'rules' => [
                                'admin:level' => 'item:role_level'
                        ]
                ],
                'delete',
                'is_system'
        ]
];