<?php

use Faxbox\Repositories\Permission\PermissionInterface;
use Faxbox\Repositories\Group\GroupInterface;
use Faxbox\Service\Form\Group\GroupForm;

class GroupController extends BaseController {

    protected $permissions;
    protected $gourps;
    
	public function __construct(PermissionInterface $permissions, GroupInterface $groups, GroupForm $groupForm)
	{
        parent::__construct();
        
        $this->permissions = $permissions;
        $this->groups = $groups;
        $this->groupForm = $groupForm;

        $this->beforeFilter('auth');
        $this->beforeFilter('hasAccess:superuser');
	}

    public function index()
    {

    }

    public function create()
    {
        $permissions = $this->permissions->all();
        $this->view('groups.create', ['permissions' => $permissions]);
    }

    public function store()
    {
        // Form Processing
        $result = $this->groupForm->save( Input::all() );
        
        if( $result['success'] )
        {
            // Success!
            Session::flash('success', $result['message']);
            return Redirect::action('GroupController@index');

        } else {
            Session::flash('error', $result['message']);
            return Redirect::action('GroupController@create')
                           ->withInput()
                           ->withErrors( $this->groupForm->errors() );
        }
    }

    public function show()
    {

    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }

}
