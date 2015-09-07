<?php

Route::group(['prefix' => 'product', 'namespace' => 'Modules\Product\Http\Controllers'], function()
{
	Route::get('/', 'ProductController@index');
});