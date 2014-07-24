<?php
namespace Faxbox\Repositories\User;

use Cartalyst\Sentry\Sentry;
use Illuminate\Support\Str;

class SentryUser implements UserInterface {

    protected $sentry;

    /**
     * Construct a new SentryUser Object
     */
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
        $result = [];
        try {
            //Attempt to register the user. 
            $user = $this->sentry->register(['email' => e($data['email']), 'password' => e($data['password'])], true);

            //success!
            $result['success'] = true;
            $result['message'] = trans('users.created');
            $result['mailData']['activationCode'] = $user->GetActivationCode();
            $result['mailData']['userId'] = $user->getId();
            $result['mailData']['email'] = e($data['email']);
        }
        catch (\Cartalyst\Sentry\Users\LoginRequiredException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('users.loginreq');
        }
        catch (\Cartalyst\Sentry\Users\UserExistsException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('users.exists');
        }

        return $result;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  array $data
     * @return Response
     */
    public function update($data)
    {
        $result = [];
        try
        {
            // Find the user using the user id
            $user = $this->sentry->findUserById($data['id']);

            // Update the user details
            $user->first_name = e($data['firstName']);
            $user->last_name = e($data['lastName']);

            // Only Admins should be able to change group memberships. 
            $operator = $this->sentry->getUser();
            if ($operator->hasAccess('admin'))
            {
                // Update group memberships
                $allGroups = $this->sentry->getGroupProvider()->findAll();
                foreach ($allGroups as $group)
                {
                    if (isset($data['groups'][$group->id]))
                    {
                        //The user should be added to this group
                        $user->addGroup($group);
                    } else {
                        // The user should be removed from this group
                        $user->removeGroup($group);
                    }
                }
                
                // Update User permissions
                $availablePermissions = array_column(
                    \Config::get('faxbox.permissions'), 'name'
                );
                
            }

            // Update the user
            if ($user->save())
            {
                // User information was updated
                $result['success'] = true;
                $result['message'] = trans('users.updated');
            }
            else
            {
                // User information was not updated
                $result['success'] = false;
                $result['message'] = trans('users.notupdated');
            }
        }
        catch (\Cartalyst\Sentry\Users\UserExistsException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('users.exists');
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('users.notfound');
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        try
        {
            // Find the user using the user id
            $user = $this->sentry->findUserById($id);

            // Delete the user
            $user->delete();
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            return false;
        }
        return true;
    }

    /**
     * Attempt activation for the specified user
     * @param  int $id
     * @param  string $code
     * @return bool
     */
    public function activate($id, $code)
    {
        $result = [];
        try
        {
            // Find the user using the user id
            $user = $this->sentry->findUserById($id);

            // Attempt to activate the user
            if ($user->attemptActivation($code))
            {
                // User activation passed
                $result['success'] = true;
                $url = route('login');
                $result['message'] = trans('users.activated', ['url' => $url]);
            }
            else
            {
                // User activation failed
                $result['success'] = false;
                $result['message'] = trans('users.notactivated');
            }
        }
        catch(\Cartalyst\Sentry\Users\UserAlreadyActivatedException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('users.alreadyactive');
        }
        catch (\Cartalyst\Sentry\Users\UserExistsException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('users.exists');
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('users.notfound');
        }
        return $result;
    }

    /**
     * Resend the activation email to the specified email address
     * @param  Array $data
     * @return Response
     */
    public function resend($data)
    {
        $result = [];
        try {
            //Attempt to find the user. 
            $user = $this->sentry->getUserProvider()->findByLogin(e($data['email']));

            if (!$user->isActivated())
            {
                //success!
                $result['success'] = true;
                $result['message'] = trans('users.emailconfirm');
                $result['mailData']['activationCode'] = $user->GetActivationCode();
                $result['mailData']['userId'] = $user->getId();
                $result['mailData']['email'] = e($data['email']);
            }
            else
            {
                $result['success'] = false;
                $result['message'] = trans('users.alreadyactive');
            }

        }
        catch(\Cartalyst\Sentry\Users\UserAlreadyActivatedException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('users.alreadyactive');
        }
        catch (\Cartalyst\Sentry\Users\UserExistsException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('users.exists');
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('users.notfound');
        }
        return $result;
    }

    /**
     * Handle a password reset request
     * @param  Array $data
     * @return Bool
     */
    public function forgotPassword($data)
    {
        $result = [];
        try
        {
            $user = $this->sentry->getUserProvider()->findByLogin(e($data['email']));

            $result['success'] = true;
            $result['message'] = trans('users.emailinfo');
            $result['mailData']['resetCode'] = $user->getResetPasswordCode();
            $result['mailData']['userId'] = $user->getId();
            $result['mailData']['email'] = e($data['email']);
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('users.notfound');
        }
        return $result;
    }

    /**
     * Process the password reset request
     * @param  int $id
     * @param  string $code
     * @return Array
     */
    public function resetPassword($id, $code)
    {
        $result = [];
        try
        {
            // Find the user
            $user = $this->sentry->getUserProvider()->findById($id);
            $newPassword = $this->_generatePassword();

            // Attempt to reset the user password
            if ($user->attemptResetPassword($code, $newPassword))
            {
                // Email the reset code to the user
                $result['success'] = true;
                $result['message'] = trans('users.emailpassword');
                $result['mailData']['newPassword'] = $newPassword;
                $result['mailData']['email'] = $user->getLogin();
            }
            else
            {
                // Password reset failed
                $result['success'] = false;
                $result['message'] = trans('users.problem');
            }
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('users.notfound');
        }
        return $result;
    }

    /**
     * Process a change password request.
     * @return Array $data
     */
    public function changePassword($data)
    {
        $result = [];
        try
        {
            $user = $this->sentry->getUserProvider()->findById($data['id']);

            if ($user->checkHash(e($data['oldPassword']), $user->getPassword()))
            {
                //The oldPassword matches the current password in the DB. Proceed.
                $user->password = e($data['newPassword']);

                if ($user->save())
                {
                    // User saved
                    $result['success'] = true;
                    $result['message'] = trans('users.passwordchg');
                }
                else
                {
                    // User not saved
                    $result['success'] = false;
                    $result['message'] = trans('users.passwordprob');
                }
            }
            else
            {
                // Password mismatch. Abort.
                $result['success'] = false;
                $result['message'] = trans('users.oldpassword');
            }
        }
        catch (\Cartalyst\Sentry\Users\LoginRequiredException $e)
        {
            $result['success'] = false;
            $result['message'] = 'Login field required.';
        }
        catch (\Cartalyst\Sentry\Users\UserExistsException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('users.exists');
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('users.notfound');
        }
        return $result;
    }

    /**
     * Return a specific user from the given id
     *
     * @param  integer $id
     * @return User
     */
    public function byId($id)
    {
        try
        {
            $user = $this->sentry->findUserById($id);
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            return false;
        }
        return $user;
    }

    /**
     * Return all the registered users
     *
     * @return stdObject Collection of users
     */
    public function all()
    {
        $users = $this->sentry->findAllUsers();

        foreach ($users as $user) {
            if ($user->isActivated())
            {
                $user->status = "Active";
            }
            else
            {
                $user->status = "Not Active";
            }
        }

        return $users;
    }

    /**
     * Generate password - helper function
     * From http://www.phpscribble.com/i4xzZu/Generate-random-passwords-of-given-length-and-strength
     *
     */
    private function _generatePassword($length = 10) {
        
        return Str::random($length);
    }
}