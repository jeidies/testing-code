<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    $f = new \Webarq\Manager\HTML\FormManager('http://submitter.com', 'frm-detail');
    $f->setTitle('Registration Form');
    $f->setElementLabelDefaultContainer(':webarq.form.default.label')
            ->setElementInfoDefaultContainer(':webarq.form.default.info');


    $f->addCollection('text', 'username')
            ->setContainer(':webarq.form.default.item');
    $f->addCollection('password', 'password')
            ->setLabel('Password')
            ->setInfo('Combine your password with punctuation mark, alphanumeric character');
    $f->addCollection('text', 'email', function($input){
        $input->setInfo('Insert your valid email');
    });

    $f->addCollectionGroup(
            [['text', 'full name'], null, 'Insert your name according to your ID Card'],
            ['text', 'sex', 'value', function($input){
                $input->setLabel('Gender');
            }],
            ':webarq.form.default.group');

    return '<htm><body style="margin:30px auto;width:20%;">' . $f->toHtml() . '</body></htm>';
});