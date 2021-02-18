<?php

/**
 * All route names are prefixed with 'api.log'.
 */
Route::group([
    'prefix' => 'log',
    'as' => 'log.',
    'namespace' => 'Log',
], function () {
    Route::get('{year}/{month}/{day}', 'LogController@getRow');    
    Route::get('{type}/{id}', 'LogController@getPayload');    
});
