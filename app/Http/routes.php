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

Route::group(['middleware' => 'auth'], function(){
    Route::resource('role', 'RoleController');
    Route::resource('user', 'UserController');
    Route::resource("permission", "PermissionController");
    Route::get("/assign", array(
        "as"   => "role.assign",
        "uses" => "RoleController@assign"
    ));
    Route::post("/assign", array(
        "as"   => "role.assign",
        "uses" => "RoleController@saveUserRoleAssignment"
    ));
    //	Facility controller
    Route::resource('facility', 'FacilityController');
    Route::get("/facility/{id}/delete", array(
        "as"   => "facility.delete",
        "uses" => "FacilityController@delete"
    ));
    //	Field controller
    Route::resource('field', 'FieldController');
    Route::get("/field/{id}/delete", array(
        "as"   => "field.delete",
        "uses" => "FieldController@delete"
    ));
    //	Options controller
    Route::resource('option', 'OptionController');
    Route::get("/option/{id}/delete", array(
        "as"   => "option.delete",
        "uses" => "OptionController@delete"
    ));
    //	Programs controller
    Route::resource('program', 'ProgramController');
    Route::get("/program/{id}/delete", array(
        "as"   => "program.delete",
        "uses" => "ProgramController@delete"
    ));
    //	Sample-preparation controller
    Route::resource('material', 'MaterialController');
    Route::get("/material/{id}/delete", array(
        "as"   => "material.delete",
        "uses" => "MaterialController@delete"
    ));
});

Route::get('/', function () {
    return view('app');
});
