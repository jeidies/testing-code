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
//    $g = new \Webarq\Manager\HTML\NodeManager('div.p.span');
//    dd($g);

    $f = new \Webarq\Manager\HTML\FormManager(true);
    $f->setTitle('My Form');
//    $f->addCollection('text', 'name', function($input){
//        $input->setContainer('div')->setLabel('Full Name')->setInfo('Insert your name according to your ID Card');
//    });
    $f->addCollection('text', 'name')
            ->setContainer('div')
            ->setLabel('Full Name')
            ->setInfo('Insert your name according to your ID Card');
    $f->addCollectionGroup(['text', 'email', function($input){
        $input
                ->setContainer('div')
                ->setLabel('Email')
                ->setInfo('Insert your valid email');}], ['textarea', 'address']);

    return $f->toHtml();
});