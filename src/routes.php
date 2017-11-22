<?php

Route::get('/page/{url}', 'Larrock\ComponentPages\PageController@getItem')->name('page');

Route::group(['prefix' => 'admin', 'middleware'=> ['web', 'level:2', 'LarrockAdminMenu', 'SaveAdminPluginsData', 'SiteSearchAdmin']], function(){
    Route::resource('page', 'Larrock\ComponentPages\AdminPageController');
});