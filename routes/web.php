<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/show/{id}', 'ApartmentController@show')->name('show');
Route::get('/homesponsor', 'ApartmentController@showSponsored')->name('show.sponsored');
Route::get('/search', 'ApartmentController@search')->name('search');

Route::get('/user/apartment/new', 'ApartmentController@createNewApartment')->name('create');
Route::post('/user/apartment/new', 'ApartmentController@saveNewApartment')->name('save');
Route::get('/user/sponsorship/{id}', 'SponsorshipController@showSponsorshipForm')->name('showSponsorshipForm');

Route::get('/payment/process/{amount}/{apartmentId}/{sponsorshipId}', 'SponsorshipController@process')->name('payment.process');

Route::post('/user/message/{id}', 'HomeController@storeMessage')->name('create-message');
