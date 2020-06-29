<?php


Route::group(['middleware' => 'auth.basic.once', 'namespace' => 'content\smartvideo\Controllers'], function(){

    Route::get('statements','allController@list');
    Route::get('statements/{id}','allController@show');
    Route::delete('statements/{id}','allController@destroy');

    Route::get('connection','connectController@list');
    Route::get('connection/{id}','connectController@show');
    Route::post('connection','connectController@create');
    Route::put('connection/{id}','connectController@update');
    Route::delete('connection/{id}','connectController@destroy');

    Route::get('search','searchController@list');
    Route::get('search/{id}','searchController@show');
    Route::post('search','searchController@create');
    Route::put('search/{id}','searchController@update');
    Route::delete('search/{id}','searchController@destroy');

    Route::get('selection','selectController@list');
    Route::get('selection/{id}','selectController@show');
    Route::post('selection','selectController@create');
    Route::put('selection/{id}','selectController@update');
    Route::delete('selection/{id}','selectController@destroy');

    Route::get('share','shareController@list');
    Route::get('share/{id}','shareController@show');
    Route::post('share','shareController@create');
    Route::put('share/{id}','shareController@update');
    Route::delete('share/{id}','shareController@destroy');

    Route::get('like','likeController@list');
    Route::get('like/{id}','likeController@show');
    Route::post('like','likeController@create');
    Route::put('like/{id}','likeController@update');
    Route::delete('like/{id}','likeController@destroy');

    Route::get('rating','rateController@list');
    Route::get('rating/{id}','rateController@show');
    Route::post('rating','rateController@create');
    Route::put('rating/{id}','rateController@update');
    Route::delete('rating/{id}','rateController@destroy');

    Route::get('annotation','annotateController@list');
    Route::get('annotation/{id}','annotateController@show');
    Route::post('annotation','annotateController@create');
    Route::put('annotation/{id}','annotateController@update');
    Route::delete('annotation/{id}','annotateController@destroy');

    Route::get('addition','addController@list');
    Route::get('addition/{id}','addController@show');
    Route::post('addition','addController@create');
    Route::put('addition/{id}','addController@update');
    Route::delete('addition/{id}','addController@destroy');

    Route::get('deletion','deleteController@list');
    Route::get('deletion/{id}','deleteController@show');
    Route::post('deletion','deleteController@create');
    Route::put('deletion/{id}','deleteController@update');
    Route::delete('deletion/{id}','deleteController@destroy');

    Route::get('play','playController@list');
    Route::get('play/{id}','playController@show');
    Route::post('play','playController@create');
    Route::put('play/{id}','playController@update');
    Route::delete('play/{id}','playController@destroy');

    Route::get('pause','pauseController@list');
    Route::get('pause/{id}','pauseController@show');
    Route::post('pause','pauseController@create');
    Route::put('pause/{id}','pauseController@update');
    Route::delete('pause/{id}','pauseController@destroy');

    Route::get('seeking','seekController@list');
    Route::get('seeking/{id}','seekController@show');
    Route::post('seeking','seekController@create');
    Route::put('seeking/{id}','seekController@update');
    Route::delete('seeking/{id}','seekController@destroy');

    Route::get('completion','completeController@list');
    Route::get('completion/{id}','completeController@show');
    Route::post('completion','completeController@create');
    Route::put('completion/{id}','completeController@update');
    Route::delete('completion/{id}','completeController@destroy');

    Route::get('termination','terminateController@list');
    Route::get('termination/{id}','terminateController@show');
    Route::post('termination','terminateController@create');
    Route::put('termination/{id}','terminateController@update');
    Route::delete('termination/{id}','terminateController@destroy');

});

