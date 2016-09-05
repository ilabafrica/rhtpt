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
    //	County controller
    Route::resource('county', 'CountyController');
    Route::get("/county/{id}/delete", array(
        "as"   => "county.delete",
        "uses" => "CountyController@delete"
    ));
    //	SubCounty controller
    Route::resource('subCounty', 'SubCountyController');
    Route::get("/subCounty/{id}/delete", array(
        "as"   => "subCounty.delete",
        "uses" => "SubCountyController@delete"
    ));
});

Route::get('/', function () {
    return view('app');
});
