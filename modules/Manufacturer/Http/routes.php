<?php

Route::group(['prefix' => 'manufacturer', 'namespace' => 'Modules\Manufacturer\Http\Controllers'], function()
{
	Route::get('/', 'ManufacturerController@index');
});