<?php

/**
 * All route names are prefixed with 'api.log'.
 */
Route::group([
    'prefix' => 'auth',
    'as' => 'auth.',
    'namespace' => 'Auth',
], function () {
    Route::any('totp/{code}', 'TOTPController@main'); 
});
