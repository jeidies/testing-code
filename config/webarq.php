<?php
/**
 * Created by PhpStorm
 * Date: 19/10/2016
 * Time: 16:22
 * Author: Daniel Simangunsong
 *
 * Calm seas, never make skill full sailors
 */


return [
//Project information
        'projectInfo' => [
                'author' => 'Daniel Simangunsong',
                'codename' => 'Black Mamba',
                'initDate' => 'January 01, 2016',
                'name' => 'WEBARQ Laravel CMS',
        ],

//Site configuration
        'site' => [
                'meta' => [
                        'author' => 'Daniel Simangunsong',
                        'description' => 'Some description',
                        'keyword' => 'Separate,with,comma',
                        'title' => 'Some title'
                ],
                'offline' => false
        ],

//System default configuration
        'system' => [
                'ghost' => 'ghost-parameter-should-be-unpredictable',
                'environment' => 'local',
                'secureUrl' => false,
                'queryLog' => true,
                'assetAttr' => [
                        'css' => ['type' => 'text/css', 'media' => ['all']],
                        'js' => ['type' => 'text/javascript'],
                        'img' => ['alt' => 'Image alternative text']
                ],
                'formInput' => [
                        'type' => 'text',
                        'value' => null
                ],
                'themes' => 'black-mamba',
        ],

//Themes
        'themes' => [
                'black-mamba' => [
                        'name' => 'Black Mamba',
                        'path' => [
                                'css' => 'css',
                                'image' => 'images',
                                'js' => 'js'
                        ]
                ]
        ],

//Active module
        'modules' => ['system', 'client'],

        /**
        'smallId' => [
        'name' => 'id',
        'type' => 'int',
        'primary' => true,
        'increment' => true,
        'unsigned' => true,
        // Will add form required attributes automatically
        'notnull' => true,
        'form' => [
        // Programmed form attributes
        // Input element type
        'type' => 'text|radio|checkbox|textarea|select|hidden|button|submit',
        // If input type is select, it should be have an array of options | a callback which return an array to
        'options' => [
        // Tell the manager to select some label,value from table (if any: where condition) and return it as an array
        'table' => 'table-name',
        'label' => 'label-column-name',
        'value' => 'value-column-name',
        'where' => []
        ],
        // If input type is select, with this will allow multiple option to select
        'multiple' => true,
        // Output html, it could be anything, a view factory, a callback method, a string ...
        'html' => '',
        // Input label
        'label' => 'Some string',
        // Passing an input value
        'value' => 'Some string',
        // HTML input attributes
        // Input parent container
        'container' => '<div></div>',
        'class' => 'some-class',
        'id' => 'some-id',
        'style' => 'some-style',
        // Will transform value into some slug format
        'permalink' => true,
        // Check if the given value is a valid url address
        'url' => true,
        // Check if the given value is a valid email address
        'email' => true,
        // Force an input to fill in
        'required' => true,
        // Check if the given value, already exists in a table
        'unique' => true,
        // Input rules, used when need to filtering input before render
        'rules' => [
        'guarded' => true,
        'permission' => 'is_system',
        ],
        'rules-second-way' => function ($admin, $item) {

        },
        // Inform rendering handler to print this as an input information
        'info' => 'Some information'
        ]
        ]
         */
        'data-type-master' => [
                'tinyId' => [
                        'type' => 'tinyint',
                        'length' => 4,
                        'primary' => true,
                        'increment' => true,
                        'unsigned' => true,
                        'name' => 'id',
                        'notnull' => true
                ],

                'smallId' => [
                        'type' => 'smallint',
                        'length' => 6,
                        'primary' => true,
                        'increment' => true,
                        'unsigned' => true,
                        'name' => 'id',
                        'notnull' => true
                ],

                'id' => [
                        'type' => 'int',
                        'length' => 11,
                        'primary' => true,
                        'increment' => true,
                        'unsigned' => true,
                        'name' => 'id',
                        'notnull' => true
                ],

                'bigId' => [
                        'type' => 'bigint',
                        'length' => 20,
                        'primary' => true,
                        'increment' => true,
                        'unsigned' => true,
                        'name' => 'id',
                        'notnull' => true
                ],

                'tinyInt' => [
                        'type' => 'tinyint',
                        'length' => 4,
                        'unsigned' => true,
                        'name' => 'id'
                ],

                'smallInt' => [
                        'type' => 'smallint',
                        'length' => 6,
                        'unsigned' => true,
                        'name' => 'id'
                ],

                'int' => [
                        'type' => 'int',
                        'length' => 11,
                        'unsigned' => true,
                        'name' => 'id'
                ],

                'bigInt' => [
                        'type' => 'bigint',
                        'length' => 20,
                        'unsigned' => true,
                        'name' => 'id'
                ],

                'tinySequence' => [
                        'type' => 'tinyint',
                        'length' => 4,
                        'name' => 'sequence',
                        'notnull' => true,
                        'form' => [
                                'type' => 'sequence',
                                'class' => 'sequence'
                        ]
                ],

                'smallSequence' => [
                        'type' => 'smallint',
                        'length' => 6,
                        'name' => 'sequence',
                        'notnull' => true,
                        'form' => [
                                'type' => 'sequence',
                                'class' => 'sequence'
                        ]
                ],

                'sequence' => [
                        'type' => 'int',
                        'length' => 11,
                        'name' => 'sequence',
                        'notnull' => true,
                        'form' => [
                                'type' => 'sequence',
                                'class' => 'sequence'
                        ]
                ],

                'bigSequence' => [
                        'type' => 'bigint',
                        'length' => 20,
                        'name' => 'sequence',
                        'notnull' => true,
                        'form' => [
                                'type' => 'sequence',
                                'class' => 'sequence'
                        ]
                ],

                'shortLabel' => [
                        'type' => 'varchar',
                        'length' => 25,
                        'notnull' => true,
                        'name' => 'label',
                        'form' => [
                                'type' => 'text'
                        ]
                ],

                'label' => [
                        'type' => 'varchar',
                        'length' => 100,
                        'notnull' => true,
                        'name' => 'label',
                        'form' => [
                                'type' => 'text'
                        ]
                ],

                'longLabel' => [
                        'type' => 'varchar',
                        'length' => 255,
                        'notnull' => true,
                        'name' => 'label',
                        'form' => [
                                'type' => 'text'
                        ]
                ],

                'uShortLabel' => [
                        'type' => 'varchar',
                        'length' => 25,
                        'unique' => true,
                        'notnull' => true,
                        'name' => 'label',
                        'form' => [
                                'type' => 'text'
                        ]
                ],

                'uLabel' => [
                        'type' => 'varchar',
                        'length' => 100,
                        'unique' => true,
                        'notnull' => true,
                        'name' => 'label',
                        'form' => [
                                'type' => 'text'
                        ]
                ],

                'uLongLabel' => [
                        'type' => 'varchar',
                        'length' => 255,
                        'unique' => true,
                        'notnull' => true,
                        'name' => 'label',
                        'form' => [
                                'type' => 'text'
                        ]
                ],

                'shortIntro' => [
                        'type' => 'varchar',
                        'length' => 2000,
                        'name' => 'intro',
                        'form' => [
                                'type' => 'textarea'
                        ]
                ],

                'intro' => [
                        'type' => 'varchar',
                        'length' => 4000,
                        'name' => 'intro',
                        'form' => [
                                'type' => 'textarea'
                        ]
                ],

                'description' => [
                        'type' => 'text',
                        'name' => 'description',
                        'form' => [
                                'type' => 'textarea'
                        ]
                ],

                'mediumDescription' => [
                        'type' => 'mediumtext',
                        'name' => 'description',
                        'form' => [
                                'type' => 'textarea'
                        ]
                ],

                'longDescription' => [
                        'type' => 'longtext',
                        'name' => 'description',
                        'form' => [
                                'type' => 'textarea'
                        ]
                ],

                'bool' => [
                        'type' => 'char',
                        'length' => 1,
                        'default' => 1,
                        'notnull' => true,
                        'form' => [
                                'type' => 'select',
                                'options' => ['Off', 'On']
                        ]
                ],

                'falseBool' => [
                        'type' => 'char',
                        'length' => 1,
                        'default' => 0,
                        'notnull' => true,
                        'form' => [
                                'type' => 'select',
                                'options' => ['Off', 'On']
                        ]
                ],

                'createOn' => [
                        'type' => 'datetime',
                        'name' => 'create_on',
                        'notnull' => true,
                        'form' => [
                                'type' => 'text',
                                'class' => 'datepicker'
                        ]
                ],

                'lastUpdate' => [
                        'type' => 'datetime',
                        'name' => 'last_update',
                        'form' => [
                                'type' => 'text',
                                'class' => 'datepicker'
                        ]
                ]
        ]
];