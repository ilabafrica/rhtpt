<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

Route::group(['middleware' => 'auth'], function()
{
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('welcome', function () {
        return view('welcome');
    });
    Route::get('home', function () {
        return view('welcome');
    });

    Route::get('/', function () {
        return view('app');
    });

    Route::get('item', 'VueItemController@manageVue');
    Route::resource('vueitems','VueItemController');

    Route::get('event', 'EventController@manageEvent');
    Route::resource('vueevents','EventController');

    Route::get('role', 'RoleController@manageRole');
    Route::resource('vueroles','RoleController');
    Route::any('vueroles/{id}/restore','RoleController@restore');

    Route::get('option', 'OptionController@manageOption');
    Route::resource('vueoptions','OptionController');

    Route::get('set', 'SetController@manageSet');
    Route::resource('vuesets','SetController');

    Route::get('program', 'ProgramController@manageProgram');
    Route::resource('vueprograms','ProgramController');

    Route::get('round', 'RoundController@manageRound');
    Route::resource('vuerounds','RoundController');

    Route::get('field', 'FieldController@manageField');
    Route::resource('vuefields','FieldController');

    Route::get('facility', 'FacilityController@manageFacility');
    Route::resource('vuefacilitys','FacilityController');

    Route::get('user', 'UserController@manageUser');
    Route::resource('vueusers','UserController');

    Route::get('shipper', 'ShipperController@manageShipper');
    Route::resource('vueshippers','ShipperController');

    Route::get('material', 'MaterialController@manageMaterial');
    Route::resource('vuematerials','MaterialController');

    //Route::get('item', 'ItemController@manageItem');
    //Route::resource('vueitems','ItemController');

    Route::get('expected', 'ExpectedController@manageExpected');
    Route::resource('vueexpecteds','ExpectedController');

    Route::get('shipment', 'ShipmentController@manageShipment');
    Route::resource('vueshipments','ShipmentController');

    Route::get('receipt', 'ReceiptController@manageReceipt');
    Route::resource('vuereceipts','ReceiptController');

    Route::get('manage-vue', 'VueItemController@manageVue');
    Route::resource('vueitems','VueItemController');
});