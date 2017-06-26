<?php

use Larrock\ComponentPages\AdminPageController;

$middlewares = ['web', 'GetSeo'];
if(file_exists(base_path(). '/vendor/fanamurov/larrock-menu')){
    $middlewares[] = 'AddMenuFront';
}
if(file_exists(base_path(). '/vendor/fanamurov/larrock-blocks')){
    $middlewares[] = 'AddBlocksTemplate';
}

Route::group(['middleware' => $middlewares], function(){
    Route::get('/page/{url}', [
        'as' => 'page', 'uses' => 'Larrock\ComponentPages\PageController@getItem'
    ]);
});

Route::group(['prefix' => 'admin', 'middleware'=> ['web', 'level:2', 'LarrockAdminMenu']], function(){
    Route::resource('page', AdminPageController::class);
});