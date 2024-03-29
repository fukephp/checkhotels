<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace' => 'App\Http\Controllers'], function()
{   
    /**
     * Home Routes
     */
    Route::get('/', 'HomeController@index')->name('home.index');

    Route::group(['middleware' => ['guest']], function() {
        /**
         * Register Routes
         */
        Route::get('/register', 'RegisterController@show')->name('register.show');
        Route::post('/register', 'RegisterController@register')->name('register.perform');

        /**
         * Login Routes
         */
        Route::get('/login', 'LoginController@show')->name('login.show');
        Route::post('/login', 'LoginController@login')->name('login.perform');

    });

    Route::group(['middleware' => ['auth']], function() {
        /**
         * Places
         */
        Route::get('/places', 'PlaceController@index')->name('place.index');
        Route::get('/places/create', 'PlaceController@create')->name('place.create');
        Route::post('/places/create/store', 'PlaceController@store')->name('place.store');
        Route::get('/places/{id}/view', 'PlaceController@view')->name('place.view');
        Route::get('/places/{id}/edit', 'PlaceController@edit')->name('place.edit');
        Route::post('/places/{id}/edit', 'PlaceController@update')->name('place.update');
        Route::get('/places/{id}/delete', 'PlaceController@delete')->name('place.delete');
        Route::get('/places/{id}/export/hotels', 'PlaceController@exportHotel')->name('place.hotel.export');
        Route::post('/places/{id}/export/hotels/store', 'PlaceController@exportHotelStore')->name('place.hotel.export.store');
        Route::post('/places/export', 'PlaceController@export')->name('place.export');
        /**
         * Hotels
         */
        Route::get('/hotels', 'HotelController@index')->name('hotel.index');
        Route::post('/hotels', 'HotelController@search')->name('hotel.search');
        /**
         * Import csv
         */
        Route::get('/import', 'ImportController@index')->name('import.index');
        Route::get('/import/store_palaces/{id}', 'ImportController@storePlaces')->name('import.store.places');
        Route::get('/import/delete/{id}', 'ImportController@delete')->name('import.delete');
        Route::post('/import', 'ImportController@importStore')->name('import.perform');
        /**
         * Logout Routes
         */
        Route::get('/logout', 'LogoutController@perform')->name('logout.perform');
    });
});
