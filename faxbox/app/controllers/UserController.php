<?php

use Faxbox\Repositories\User\UserInterface;
use Faxbox\Repositories\Group\GroupInterface;
use Faxbox\Service\Form\Register\RegisterForm;
use Faxbox\Service\Form\User\UserForm;
use Faxbox\Service\Form\ResendActivation\ResendActivationForm;
use Faxbox\Service\Form\ForgotPassword\ForgotPasswordForm;
use Faxbox\Service\Form\ChangePassword\ChangePasswordForm;
use Faxbox\Repositories\Permission\PermissionInterface;

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
        PermissionInterface $permissions
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
        
        //Check CSRF token on POST
        $this->beforeFilter('csrf', ['on' => 'post']);

        // Set up Auth Filters
        $this->beforeFilter('auth', ['only' => ['change']]);
        $this->beforeFilter('hasAccess:superuser',
            ['only' => ['show', 'index', 'destroy', 'edit', 'update', 'store']]);
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
        $permissions = $this->permissions->all();
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

        $currentGroups = $user->getGroups()->toArray();
        $userGroups    = [];
        foreach ($currentGroups as $group)
        {
            array_push($userGroups, $group['name']);
        }
        $allGroups = $this->group->all();

        return View::make('users.edit')
                   ->with('user', $user)
                   ->with('userGroups', $userGroups)
                   ->with('allGroups', $allGroups);
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
        $result = $this->userForm->update(Input::all());

        if ($result['success'])
        {
            // Success!
            Session::flash('success', $result['message']);

            return Redirect::action('UserController@show', [$id]);

        } else
        {
            Session::flash('error', $result['message']);

            return Redirect::action('UserController@edit', [$id])
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

        $result = $this->user->activate($id, $code);

        if ($result['success'])
        {
            // Success!
            Session::flash('success', $result['message']);

            return Redirect::route('home');

        } else
        {
            Session::flash('error', $result['message']);

            return Redirect::route('home');
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

            return Redirect::route('home');
        } else
        {
            Session::flash('error', $result['message']);

            return Redirect::route('profile')
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

            return Redirect::route('home');
        } else
        {
            Session::flash('error', $result['message']);

            return Redirect::route('forgotPasswordForm')
                           ->withInput()
                           ->withErrors($this->forgotPasswordForm->errors());
        }
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

        $result = $this->user->resetPassword($id, $code);

        if ($result['success'])
        {
            Event::fire('user.newpassword',
            [
                'email'       => $result['mailData']['email'],
                'newPassword' => $result['mailData']['newPassword']
            ]);

            // Success!
            Session::flash('success', $result['message']);

            return Redirect::route('home');

        } else
        {
            Session::flash('error', $result['message']);

            return Redirect::route('home');
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

            return Redirect::route('home');
        } else
        {
            Session::flash('error', $result['message']);

            return Redirect::action('UserController@edit', [$id])
                           ->withInput()
                           ->withErrors($this->changePasswordForm->errors());
        }
    }


}