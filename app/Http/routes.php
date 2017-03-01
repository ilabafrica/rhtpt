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
Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');
Route::get('logout', 'Auth\AuthController@getLogout');

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

    Route::get('item', 'ItemController@manageItem');
    Route::resource('vueitems','ItemController');
    Route::any('vueitems/{id}/restore','ItemController@restore');

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

    //Route::get('manage-vue', 'VueItemController@manageVue');
    //Route::resource('vueitems','VueItemController');


    Route::get("/assign", array(
        "as"   => "role.assign",
        "uses" => "RoleController@manageAssignment"
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

    Route::get("/mat", array(
        "as"   => "mat.fetch",
        "uses" => "ItemController@materials"
    ));

    Route::get("/rnds", array(
        "as"   => "rnds.fetch",
        "uses" => "ItemController@rounds"
    ));

    Route::get("/itms", array(
        "as"   => "itms.fetch",
        "uses" => "ExpectedController@items"
    ));

    Route::get("/rslts", array(
        "as"   => "rslts.fetch",
        "uses" => "ExpectedController@options"
    ));

    Route::get("/cnts", array(
        "as"   => "cnts.fetch",
        "uses" => "FacilityController@counties"
    ));

    Route::get("/subs/{id}", array(
        "as"   => "subs.fetch",
        "uses" => "FacilityController@subs"
    ));

    Route::get("/fclts/{id}", array(
        "as"   => "facilities.fetch",
        "uses" => "FacilityController@facilities"
    ));

    Route::get("/shpprs/{id}", array(
        "as"   => "shippers.fetch",
        "uses" => "ShipperController@shippers"
    ));

    Route::get("/rng", array(
        "as"   => "ranges.fetch",
        "uses" => "UserController@ranges"
    ));
    

    Route::get('settings', 'BulkSMSController@manageSettings');

    Route::get('broadcast', 'BulkSMSController@manageBroadcast');
    Route::resource('vuebroadcasts','BulkSMSController');
    
    //  Save sms gateway username and api-key
    Route::post("/bulk/api", array(
        "as"   => "bulk.api",
        "uses" => "BulkSMSController@api"
    ));
    Route::get("/bulk/key", array(
        "as"   => "bulk.key",
        "uses" => "BulkSMSController@key"
    ));
    //  Form for composing and sending SMS
    Route::get("/bulk/compose", array(
        "as"   => "bulk.compose",
        "uses" => "BulkSMSController@compose"
    ));
    Route::post("/bulk/send", array(
        "as"   => "bulk.send",
        "uses" => "BulkSMSController@broadcast"
    ));
    Route::get("/bulk/broadcast", array(
        "as"   => "bulk.broadcast",
        "uses" => "BulkSMSController@bulk"
    ));
    Route::get("/sms/{id}", array(
        "as"   => "sms.bulk",
        "uses" => "BulkSMSController@sms"
    ));
    //  Receive shipment
    Route::post("/receive", array(
        "as"   => "shipment.receive",
        "uses" => "ShipmentController@receive"
    ));

    Route::get('result', 'ResultController@manageResult');
    Route::resource('vueresults','ResultController');
    Route::any('vueresults/{id}/restore','ResultController@restore');
    Route::get("/pt/{id}", array(
        "as"   => "pt.fetch",
        "uses" => "ResultController@edit"
    ));

    Route::get("/form", array(
        "as"   => "fields.fetch",
        "uses" => "QuestionnaireController@fetch"
    ));

    Route::get('permission', 'PermissionController@managePermissions');
    Route::resource('vuepermissions','PermissionController');

    Route::get('assign', 'AssignmentController@manageAssignments');
    Route::resource('vueassigns','AssignmentController');

    Route::get("/ntfctns", array(
        "as"   => "notifications.fetch",
        "uses" => "NotificationController@fetch"
    ));
    Route::get("/tmplt/{id}", array(
        "as"   => "template.fetch",
        "uses" => "NotificationController@template"
    ));
});