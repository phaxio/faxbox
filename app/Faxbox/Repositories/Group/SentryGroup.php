<?php namespace Faxbox\Repositories\Group;

use Cartalyst\Sentry\Sentry;
use Faxbox\Repositories\Permission\PermissionInterface;

class SentryGroup implements GroupInterface {

    /**
     * The sentry object 
     * 
     * @var Sentry
     */
    protected $sentry;
    protected $permissions;

    public function __construct(Sentry $sentry, PermissionInterface $permissions)
    {
        $this->sentry = $sentry;
        $this->permissions = $permissions;
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
            // Create the group
            $group = $this->sentry->createGroup([
                'name'        => e($data['name']),
                'permissions' => $data['permissions'],
            ]);

            $result['success'] = true;
            $result['message'] = trans('groups.created');
        }
        catch (\Cartalyst\Sentry\Groups\NameRequiredException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('groups.namereq');
        }
        catch (\Cartalyst\Sentry\Groups\GroupExistsException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('groups.exists');;
        }

        return $result;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($data)
    {
        try
        {
            // Find the group using the group id
            $group = $this->sentry->findGroupById($data['id']);
            
            foreach($data['users'] as $id => $access)
            {
                $user = $this->sentry->findUserById($id);
                
                if($access){
                    $user->addGroup($group);
                }
                else {
                    $user->removeGroup($group);
                }
                
                // todo add error checking
            }
            
            // Update the group details
            $group->permissions = $data['permissions'];

            $group->name = $data['name'];
            
            // Update the group
            if ($group->save())
            {
                // Group information was updated
                $result['success'] = true;
                $result['message'] = trans('groups.updated');
            }
            else
            {
                // Group information was not updated
                $result['success'] = false;
                $result['message'] = trans('groups.updateproblem');
            }
        }
        catch (\Cartalyst\Sentry\Groups\NameRequiredException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('groups.namereq');;
        }
        catch (\Cartalyst\Sentry\Groups\GroupExistsException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('groups.groupexists');;
        }
        catch (\Cartalyst\Sentry\Groups\GroupNotFoundException $e)
        {
            $result['success'] = false;
            $result['message'] = trans('groups.notfound');
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
            // Find the group using the group id
            $group = $this->sentry->findGroupById($id);

            // Delete the group
            $group->delete();
        }
        catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
        {
            return false;
        }
        return true;
    }

    /**
     * Return a specific group by a given id
     *
     * @param  integer $id
     * @return \Cartalyst\Sentry\Group
     */
    public function byId($id)
    {
        try
        {
            $group = $this->sentry->findGroupById($id);
        }
        catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
        {
            return false;
        }
        return $group;
    }

    /**
     * Return a specific group by a given name
     *
     * @param  string $name
     * @return Group
     */
    public function byName($name)
    {
        try
        {
            $group = $this->sentry->findGroupByName($name);
        }
        catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e)
        {
            return false;
        }
        return $group;
    }

    /**
     * Return all the registered groups
     *
     * @return stdObject Collection of groups
     */
    public function all()
    {
        if($this->sentry->getUser()->isSuperUser())
        {
            $groups = $this->sentry->findAllGroups();
        } else
        {
            $groups = $this->sentry->getUser()->getGroups();
        }
        
        return $groups;
    }
    
    public function allWithUsers()
    {
        $groups = $this->all();
        
        foreach ($groups as &$group)
        {
            $group['users'] = $this->sentry->findAllUsersInGroup($group)->lists('id');
            $group = $group->toArray();
            $group['permissions'] = $this->permissions->allWithChecked($group['permissions'], 0);
        }
        
        return $groups;
    }

    public function withUsers($id)
    {
        $group = $this->sentry->findGroupById($id);
        
        $group['users'] = $this->sentry->findAllUsersInGroup($group)->lists('id');
        $group = $group->toArray();
        $group['permissions'] = $this->permissions->allWithChecked($group['permissions'], 0);
        
        return $group;
    }

    public function allWithChecked($resource = null)
    {
        $resourceGroups = [];
        
        if($resource instanceof \Cartalyst\Sentry\Users\UserInterface)
        {
            $resourceGroups = array_column($resource->getGroups()->toArray(), 'id');
        } else
        {
            foreach($this->sentry->findAllGroups() as $group)
            {
                if($group->hasAccess($resource))
                {
                    $resourceGroups[] = $group->getId();
                }
            }
        }
        
        $groups = $this->all();

        foreach ($groups as &$group)
        {
            $group['value'] = 0;
            if(in_array($group['id'], $resourceGroups)) $group['value'] = 1; 
        }

        return $groups;
    }
    
}