<?php

Route::get('/page/{url}', 'Larrock\ComponentPages\PageController@getItem')->name('page');

Route::group(['prefix' => 'admin'], function(){
    Route::resource('page', 'Larrock\ComponentPages\AdminPageController');
});

Breadcrumbs::register('admin.'. LarrockPages::getName() .'.index', function($breadcrumbs){
    $breadcrumbs->push(LarrockPages::getTitle(), '/admin/'. LarrockPages::getName());
});