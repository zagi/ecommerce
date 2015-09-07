<?php

Route::group(['prefix' => 'category', 'namespace' => 'Modules\Category\Http\Controllers'], function()
{
	Route::get('/', 'CategoryController@index');
});