<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Api',
    'middleware' => ['web', 'api'],
], function ($route) {
    $route->get('cities', 'CitiesController@listing');
    $route->get('cities/{lon?}/{lat?}', 'CitiesController@listing');
    $route->get('cities-weather-available', 'CitiesController@listingWeatherAvailable');
    $route->get('city/{cityName}', 'CitiesController@show');
    $route->get('city/{cityName}/{from?}/{to?}', 'CitiesController@show');
});
