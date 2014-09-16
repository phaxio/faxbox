<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

use Faxbox\Repositories\Permission\PermissionRepository as Permissions;

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
    if (!Sentry::check()) return Redirect::guest('login');
});

Route::filter('hasAccess', function($route, $request, $value)
{
    if (!Sentry::check()) return Redirect::guest('login');
    
    $userId = Route::input('users');
    
    try
    {
        $user = Sentry::getUser();

        if( $user->hasAccess($value) || $userId == $user->getId()) return;
    
        Session::flash('error', trans('users.noaccess'));
        return Redirect::route('home');
    }
    catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
    {
        Session::flash('error', trans('users.notfound'));
        return Redirect::guest('login');
    }

    catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
    {
        Session::flash('error', trans('groups.notfound'));
        return Redirect::guest('login');
    }
});

Route::filter('accessResource', function($route, $request, $value)
{
    if (!Sentry::check()) return Redirect::guest('login');
    
    list($class, $permission) = explode('_', $value);

    try
    {
        $user = Sentry::getUser();

        // todo add more generic check in here to lookup access based on resource ID
        if ( $user->hasAccess($value) || 
             $user->hasAccess(Permissions::name($class, 'admin'))
        ) return;

        Session::flash('error', trans('users.noaccess'));
        return Redirect::route('home');
    }
    catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
    {
        Session::flash('error', trans('users.notfound'));
        return Redirect::guest('login');
    }

    catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
    {
        Session::flash('error', trans('groups.notfound'));
        return Redirect::guest('login');
    }
    
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

Route::filter('checkInstalled', function($route, $request){

    if(!isUsingLocalStorage())
    {
        try
        {
            Setting::get('faxbox.name', true);
        } catch (PDOException $e)
        {
            // Make sure we only redirect to install if we're told by mysql the 
            // DB doesn't exist. We don't want to accidentally get here if mysql 
            // goes down
            if ($e->getCode() == '1049' && ($request->getRequestUri() != '/install'))
            {
                return Redirect::action('InstallController@index');
            }
        }
    }else
    {
        $exists = file_exists(base_path('userdata/.env.php'));

        if (!$exists && ($request->getRequestUri() != '/install'))
            return Redirect::action('InstallController@index');
    }
    
    
});