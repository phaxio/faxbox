<?php
namespace Faxbox\Repositories\User;

use Cartalyst\Sentry\Sentry;
//use Faxbox\Repositories\Permission\PermissionInterface as Permissions;
use Faxbox\Repositories\Permission\PermissionInterface;
use Illuminate\Support\Str;

class SentryUser implements UserInterface {

    protected $sentry;

    /**
     * Construct a new SentryUser Object
     */
    public function __construct(Sentry $sentry)
    {
        $this->sentry = $sentry;
        $this->permissions = \App::make('Faxbox\Repositories\Permission\PermissionInterface');
    }
    
    public function isAdmin($id)
    {
        return $this->byId($id)->isSuperUser();
    }
    
    public function isActivated($id)
    {
        $user = $this->byId($id);
        
        return $user->isActivated();
    }

    public function hasLoggedIn($id)
    {
        $user = $this->byId($id);

        return $user->last_login ? true : false;
    }

    public function resetCode($id)
    {
        return $this->byId($id)->getResetPasswordCode();
    }
    
    public function allowedResourceIds($level, $resourceClass, $userId)
    {
        return $this->permissions->allowedResourceIds($level, $resourceClass, $userId);
    }
    
    public function loggedInUserId()
    {
        $user = $this->sentry->getUser();
        
        if($user) return $user->getId();
        
        return null;
        
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
            $user = $this->sentry->register(['email' => e($data['email']), 'password' => e($data['password'])]);

            if(isset($data['groups']))
            {
                foreach ($data['groups'] as $id => $access)
                {
                    $group = $this->sentry->findGroupById($id);

                    if ($access)
                    {
                        $user->addGroup($group);
                    } else
                    {
                        $user->removeGroup($group);
                    }

                    // todo add error checking
                }
            }
            
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->permissions = isset($data['permissions']) ? $data['permissions'] : '';
            $user->save();

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
            $user->first_name = e($data['first_name']);
            $user->last_name = e($data['last_name']);
            $user->sent_notification = e($data['sent_notification']);
            $user->received_notification = e($data['received_notification']);

            // Only Admins should be able to change group memberships. 
            $operator = $this->sentry->getUser();
            if ($operator->isSuperUser())
            {
                $user->permissions = $data['permissions'];

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
                $data = $this->forgotPassword(['email' => $user->email]);
                
                $result['success'] = true;
                $url = route('login');
                $result['message'] = trans('users.activated', ['url' => $url]);
                $result['resetCode'] = $data['mailData']['resetCode'];
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
    
    public function deactivate($id)
    {
        $user = $this->byId($id);
        
        $user->activated = false;
        
        if($user->save())
        {
            $result['success'] = true;
            $result['message'] = trans('users.deactivated');
        }else
        {
            $result['success'] = false;
            $result['message'] = trans('users.generalproblem');
        }
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
    public function resetPassword($data)
    {
        $result = [];
        try
        {
            // Find the user
            $user = $this->sentry->getUserProvider()->findById($data['id']);

            // Attempt to reset the user password
            if ($user->attemptResetPassword($data['code'], $data['password']))
            {
                // Email the reset code to the user
                $result['success'] = true;
                $result['message'] = trans('users.passwordchg');
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
     * @return \Cartalyst\Sentry\Users\UserInterface
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
    
    public function getIdByLoginName($username)
    {
        try
        {
            // Get the current active/logged in user
            $user = \Sentry::findUserByLogin($username);
            \Log::info(print_r($user, true));
            return $user->getId();
        }
        catch (\Cartalyst\Sentry\Users\UserNotFoundException $e)
        {
            return null;
        }
        
        return null;
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
     *
     */
    private function _generatePassword($length = 10) {
        
        return Str::random($length);
    }
}