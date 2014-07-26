<?php

use Faxbox\Repositories\Permission\PermissionInterface;
use Faxbox\Repositories\Group\GroupInterface;

class GroupController extends BaseController {

    protected $permissions;
    protected $gourps;
    
	public function __construct(PermissionInterface $permissions, GroupInterface $groups)
	{
        parent::__construct();
        
        $this->permissions = $permissions;
        $this->groups = $groups;

        $this->beforeFilter('auth');
        $this->beforeFilter('inGroup:admins');
	}

    public function index()
    {

    }

    public function create()
    {

    }

    public function store()
    {

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
