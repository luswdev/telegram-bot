<?php

/**
 * All route names are prefixed with 'api.bot'.
 */
Route::group([
    'prefix' => 'bot',
    'as' => 'bot.',
    'namespace' => 'Bot',
], function () {
    Route::any('main', 'C3POBotController@main');    
    Route::any('github', 'C3POBotController@github');    
    Route::any('url', 'UrlBotController@main'); 
    Route::get('pay/{id}/{date}/{name}/{pay}', 'NdhuPayBotController@notiNew');
    Route::get('pay/{id}', 'NdhuPayBotController@notiOld');
    Route::any('pay', 'NdhuPayBotController@main');
    Route::any('punch/{code}/{work}', 'PunchBotController@punchIn');
    Route::any('test', 'NdhuPayBotController@test');
});
