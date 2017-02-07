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
    Route::any('vueroles/{id}/restore','VueItemController@restore');

    Route::get('event', 'EventController@manageEvent');
    Route::resource('vueevents','EventController');

    Route::get('role', 'RoleController@manageRole');
    Route::resource('vueroles','RoleController');
    Route::any('vueroles/{id}/restore','RoleController@restore');

    Route::get('option', 'OptionController@manageOption');
    Route::resource('vueoptions','OptionController');
    Route::any('vueoptions/{id}/restore','OptionController@restore');

    Route::get('set', 'SetController@manageSet');
    Route::resource('vuesets','SetController');
    Route::any('vuesets/{id}/restore','SetController@restore');

    Route::get('program', 'ProgramController@manageProgram');
    Route::resource('vueprograms','ProgramController');
    Route::any('vueprograms/{id}/restore','ProgramController@restore');

    Route::get('round', 'RoundController@manageRound');
    Route::resource('vuerounds','RoundController');
    Route::any('vuerounds/{id}/restore','RoundController@restore');

    Route::get('field', 'FieldController@manageField');
    Route::resource('vuefields','FieldController');
    Route::any('vuefields/{id}/restore','FieldController@restore');

    Route::get('facility', 'FacilityController@manageFacility');
    Route::resource('vuefacilitys','FacilityController');
    Route::any('vuefacilitys/{id}/restore','FacilityController@restore');

    Route::get('user', 'UserController@manageUser');
    Route::resource('vueusers','UserController');
    Route::any('vueusers/{id}/restore','UserController@restore');

    Route::get('shipper', 'ShipperController@manageShipper');
    Route::resource('vueshippers','ShipperController');
    Route::any('vueshippers/{id}/restore','ShipperController@restore');

    Route::get('material', 'MaterialController@manageMaterial');
    Route::resource('vuematerials','MaterialController');
    Route::any('vuematerials/{id}/restore','MaterialController@restore');

    //Route::get('item', 'ItemController@manageItem');
    //Route::resource('vueitems','ItemController');

    Route::get('expected', 'ExpectedController@manageExpected');
    Route::resource('vueexpecteds','ExpectedController');
    Route::any('vueexpecteds/{id}/restore','ExpectedController@restore');

    Route::get('shipment', 'ShipmentController@manageShipment');
    Route::resource('vueshipments','ShipmentController');
    Route::any('vueshipments/{id}/restore','ShipmentController@restore');

    Route::get('receipt', 'ReceiptController@manageReceipt');
    Route::resource('vuereceipts','ReceiptController');
    Route::any('vuereceipts/{id}/restore','ReceiptController@restore');

    Route::get('manage-vue', 'VueItemController@manageVue');
    Route::resource('vueitems','VueItemController');


    Route::get("/assign", array(
        "as"   => "role.assign",
        "uses" => "RoleController@assign"
    ));
    Route::post("/assign", array(
        "as"   => "role.assign",
        "uses" => "RoleController@saveUserRoleAssignment"
    ));

    Route::get("/st", array(
        "as"   => "st.fetch",
        "uses" => "ShipperController@options"
    ));

    Route::get("/mt", array(
        "as"   => "mt.fetch",
        "uses" => "MaterialController@options"
    ));
});