<?php namespace Faxbox\Repositories\Session;

use Cartalyst\Sentry\Sentry;

class SentrySession implements SessionInterface {

    protected $sentry;

    public function __construct(Sentry $sentry)
    {
        $this->sentry = $sentry;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($data)
    {
        $result = array();
        try
        {
            // Check for 'rememberMe' in POST data
            if (!array_key_exists('rememberMe', $data)) $data['rememberMe'] = 0;

            // Set login credentials
            $credentials = array(
                'email'    => e($data['email']),
                'password' => e($data['password'])
            );

            // Try to authenticate the user
            $user = $this->sentry->authenticate($credentials, e($data['rememberMe']));

            $result['success'] = true;
            $result['sessionData']['userId'] = $user->id;
            $result['sessionData']['email'] = $user->email;
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            // Sometimes a user is found, however hashed credentials do
            // not match. Therefore a user technically doesn't exist
            // by those credentials. Check the error message returned
            // for more information.
            $result['success'] = false;
            $result['message'] = trans('sessions.invalid');
        }
        catch (\Cartalyst\Sentry\Users\UserNotActivatedException $e)
        {
            $result['success'] = false;
            $url = route('resendActivationForm', ['email' => $data['email']]);
            
            $user = \Sentry::findUserByLogin($data['email']);
            if($user->activated_at)
            {
                $result['message'] = trans('users.deactivated');
            } else 
            {
                $result['message'] = trans('sessions.notactive', array('url' => $url));
            }
            
        }
        catch (\Cartalyst\Sentry\Throttling\UserBannedException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('sessions.banned');
        }

        //Login was succesful.  
        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
        $this->sentry->logout();
    }


}