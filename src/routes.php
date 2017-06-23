<?php

use Larrock\ComponentPages\AdminPageController;

Route::group(['middleware' => ['web', 'AddMenuFront', 'GetSeo', 'AddBlocksTemplate']], function(){
    Route::get('/page/{url}', [
        'as' => 'page', 'uses' => 'Larrock\ComponentPages\PageController@getItem'
    ]);
});

Route::group(['prefix' => 'admin', 'middleware'=> ['web', 'level:2', 'LarrockAdminMenu']], function(){
    Route::resource('page', AdminPageController::class);
});