<?php

Route::group(['prefix' => 'cart', 'namespace' => 'Modules\Cart\Http\Controllers'], function()
{
	Route::get('/', 'CartController@index');
});