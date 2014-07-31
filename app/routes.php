<?php 

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// Session Routes
Route::get('login',  array('as' => 'login', 'uses' => 'SessionController@create'));
Route::get('logout', array('as' => 'logout', 'uses' => 'SessionController@destroy'));
Route::resource('sessions', 'SessionController', array('only' => array('create', 'store', 'destroy')));


// User Routes
Route::get('users/{id}/login/{code}', 'UserController@activate')->where('id', '[0-9]+');
Route::get('resend', array('as' => 'resendActivationForm', function()
{
    return View::make('users.resend');
}));
Route::post('resend', 'UserController@resend');
Route::get('forgot', array('as' => 'forgotPasswordForm', function()
{
    return View::make('users.forgot');
}));
Route::post('forgot', 'UserController@forgot');
Route::post('users/{id}/change', 'UserController@change');
Route::get('users/{id}/reset/{code}', 'UserController@resetForm')->where('id', '[0-9]+');
Route::post('users/{id}/reset/{code}', 'UserController@reset')->where('id', '[0-9]+');
Route::resource('users', 'UserController');

Route::get('dashboard', [ 'as' => 'dashboard', 'before' => 'auth', function(){ 
    return View::make('layouts.main')->nest('content', 'dashboard.index');
}]);



// Fax/Phone Routes
Route::get('faxes', 'FaxController@index');
Route::get('faxes/received/{number}', 'PhoneController@show');

Route::resource('faxes', 'FaxController', array('only' => array('create', 'store', 'show', 'index')));
Route::resource('phones', 'PhoneController');


// Settings Routes
Route::resource('settings', 'SettingController');


// Group Routes
Route::resource('groups', 'GroupController');


// Our home route
Route::get('/', ['as' => 'home', 'before' => 'auth', 'uses' => 'HomeController@index']);

Route::post('test', function(){
    dd(Input::all());
});