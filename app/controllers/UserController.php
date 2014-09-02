<?php

use Faxbox\Repositories\User\UserInterface;
use Faxbox\Repositories\Group\GroupInterface;
use Faxbox\Service\Form\Register\RegisterForm;
use Faxbox\Service\Form\User\UserForm;
use Faxbox\Service\Form\ResendActivation\ResendActivationForm;
use Faxbox\Service\Form\ForgotPassword\ForgotPasswordForm;
use Faxbox\Service\Form\ChangePassword\ChangePasswordForm;
use Faxbox\Repositories\Permission\PermissionInterface;
use Faxbox\Service\Form\ResetPassword\ResetPasswordForm;
use Faxbox\Repositories\Session\SessionInterface;

class UserController extends BaseController {

    protected $user;
    protected $group;
    protected $registerForm;
    protected $userForm;
    protected $resendActivationForm;
    protected $forgotPasswordForm;
    protected $changePasswordForm;
    protected $suspendUserForm;
    protected $permissions;
    protected $session;

    /**
     * Instantiate a new UserController
     */
    public function __construct(
        UserInterface $user,
        GroupInterface $group,
        RegisterForm $registerForm,
        UserForm $userForm,
        ResendActivationForm $resendActivationForm,
        ForgotPasswordForm $forgotPasswordForm,
        ChangePasswordForm $changePasswordForm,
        PermissionInterface $permissions,
        ResetPasswordForm $resetPasswordForm,
        SessionInterface $session
    ) {
        parent::__construct();

        $this->user                 = $user;
        $this->group                = $group;
        $this->registerForm         = $registerForm;
        $this->userForm             = $userForm;
        $this->resendActivationForm = $resendActivationForm;
        $this->forgotPasswordForm   = $forgotPasswordForm;
        $this->changePasswordForm   = $changePasswordForm;
        $this->permissions          = $permissions;
        $this->resetPasswordForm    = $resetPasswordForm;
        $this->session = $session;
        
        // Set up Auth Filters
        $this->beforeFilter('auth', ['except' => ['activate', 'forgot', 'resetForm', 'reset']]);
        $this->beforeFilter('hasAccess:superuser',
            ['only' => ['show', 'index', 'destroy', 'edit', 'update', 'store', 'create', 'resend']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = $this->user->all();

        $this->view('users.list', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return Response
     */
    public function create()
    {
        $groups      = $this->group->all();
        $permissions = $this->permissions->allWithChecked();
        $password    = \Str::random(16);
        
        $this->view('users.create', compact('groups', 'permissions', 'password'));
    }

    /**
     * Store a newly created user.
     *
     * @return Response
     */
    public function store()
    {
        // Form Processing
        $result = $this->registerForm->save(Input::all());
        
        if ($result['success'])
        {
            Event::fire('user.signup',
            [
                'email'          => $result['mailData']['email'],
                'userId'         => $result['mailData']['userId'],
                'activationCode' => $result['mailData']['activationCode']
            ]);

            // Success!
            Session::flash('success', $result['message']);

            return Redirect::action('UserController@index');

        } else
        {
            Session::flash('error', $result['message']);

            return Redirect::action('UserController@create')
                           ->withInput()
                           ->withErrors($this->registerForm->errors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->user->byId($id);

        if ($user == null || !is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

        return View::make('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->user->byId($id);
        
        if ($user == null || !is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }
        
        $groups      = $this->group->allWithChecked($user);
        $permissions = $this->permissions->allWithChecked($user->getPermissions(), 0);

        $this->view('users.edit', compact('groups', 'permissions', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function update($id)
    {
        if (!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

        // Form Processing
        $result = $this->userForm->setCurrent($id)->update(Input::all());
        
        if ($result['success'])
        {
            // Success!
            Session::flash('success', $result['message']);

            return Redirect::action('UserController@edit', ['id' => $id]);

        } else
        {
            Session::flash('error', $result['message'] ?: 'Please fix the errors ');

            return Redirect::action('UserController@edit', ['id' => $id])
                           ->withInput()
                           ->withErrors($this->userForm->errors());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        if (!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

        if ($this->user->destroy($id))
        {
            Session::flash('success', 'User Deleted');

            return Redirect::to('/users');
        } else
        {
            Session::flash('error', 'Unable to Delete User');

            return Redirect::to('/users');
        }
    }

    /**
     * Activate a new user
     *
     * @param  int    $id
     * @param  string $code
     *
     * @return Response
     */
    public function activate($id, $code)
    {
        if (!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

        if($this->user->isActivated($id) && !$this->user->hasLoggedIn($id))
        {
            return Redirect::action('UserController@resetForm', ['id' => $id, 'code' => $this->user->resetCode($id)]);
        }
        else if($this->user->isActivated($id))
        {
            return Redirect::route('home');
        }
        
        // activate the user
        $result = $this->user->activate($id, $code);

        if ($result['success'])
        {
            // Success!
            Session::flash('success', $result['message']);

            return Redirect::action('UserController@resetForm', ['id' => $id, 'code' => $result['resetCode']]);

        } else
        {
            Session::flash('error', $result['message']);

            return Redirect::route('login');
        }
    }
    
    public function deactivate($id)
    {
        $result = $this->user->deactivate($id);

        if ($result['success'])
        {
            // Success!
            Session::flash('success', $result['message']);

            return Redirect::action('UserController@index');

        } else
        {
            Session::flash('error', $result['message']);

            return Redirect::action('UserController@index');
        }
    }

    /**
     * Process resend activation request
     * @return Response
     */
    public function resend()
    {
        // Form Processing
        $result = $this->resendActivationForm->resend(Input::all());

        if ($result['success'])
        {
            Event::fire('user.resend',
            [
                'email'          => $result['mailData']['email'],
                'userId'         => $result['mailData']['userId'],
                'activationCode' => $result['mailData']['activationCode']
            ]);

            // Success!
            Session::flash('success', $result['message']);

            return Redirect::action('UserController@index');
        } else
        {
            Session::flash('error', $result['message']);

            return Redirect::action('UserController@index')
                           ->withInput()
                           ->withErrors($this->resendActivationForm->errors());
        }
    }

    /**
     * Process Forgot Password request
     * @return Response
     */
    public function forgot()
    {
        // Form Processing
        $result = $this->forgotPasswordForm->forgot(Input::all());

        if ($result['success'])
        {
            Event::fire('user.forgot',
            [
                'email'     => $result['mailData']['email'],
                'userId'    => $result['mailData']['userId'],
                'resetCode' => $result['mailData']['resetCode']
            ]);

            // Success!
            Session::flash('success', $result['message']);

            return Redirect::route('login');
        } else
        {
            Session::flash('error', $result['message']);

            return Redirect::route('forgotPasswordForm')
                           ->withInput()
                           ->withErrors($this->forgotPasswordForm->errors());
        }
    }
    
    public function resetForm($id, $code)
    {
        return View::make('users.reset', compact('id', 'code'));
    }

    /**
     * Process a password reset request link
     *
     * @param  [type] $id   [description]
     * @param  [type] $code [description]
     *
     * @return [type]       [description]
     */
    public function reset($id, $code)
    {
        if (!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

        // Form Processing
        $result = $this->resetPasswordForm->reset(Input::all());

        if ($result['success'])
        {
            // Success!
            Session::flash('success', $result['message']);

            $email = $this->user->byId($id)->email;
            
            $this->session->store([
                'email' => $email, 
                'password' => Input::get('password')
            ]);
            
            Event::fire('user.login', [
                'userId' => $id,
                'email' => $email
            ]);
            
            return Redirect::route('home');

        } else
        {
            Session::flash('error', $result['message']);

            return Redirect::action('UserController@resetForm', compact('id', 'code'))->withErrors($this->resetPasswordForm->errors());
        }
    }

    /**
     * Process a password change request
     *
     * @param  int $id
     *
     * @return redirect
     */
    public function change($id)
    {
        if (!is_numeric($id))
        {
            // @codeCoverageIgnoreStart
            return \App::abort(404);
            // @codeCoverageIgnoreEnd
        }

        $data       = Input::all();
        $data['id'] = $id;

        // Form Processing
        $result = $this->changePasswordForm->change($data);

        if ($result['success'])
        {
            // Success!
            Session::flash('success', $result['message']);

            return Redirect::route('login');
        } else
        {
            Session::flash('error', $result['message']);

            return Redirect::action('UserController@edit', [$id])
                           ->withInput()
                           ->withErrors($this->changePasswordForm->errors());
        }
    }


}