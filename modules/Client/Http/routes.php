<?php

Route::group(['prefix' => 'client', 'namespace' => 'Modules\Client\Http\Controllers'], function()
{
	Route::get('/', 'ClientController@index');
});