<?php namespace Faxbox\Repositories\Permission;

use \Cartalyst\Sentry\Users\UserInterface;
Use Config;
use Faxbox\Repositories\Phone\PhoneInterface;

class PermissionRepository implements PermissionInterface{

    /**
     * The raw permissions config array
     * 
     * @var array
     */
    protected $raw;
    
    /**
     * Available permissions taken from the config
     * 
     * @var array
     */
    protected $available;
    
    public function __construct(Config $config)
    {
        $this->raw = Config::get('faxbox.permissions');
    }
    
    public function all()
    {
        return $this->_getAvailablePermissions();
    }

    public function allWithChecked($checkedPermissions)
    {
        $available = $this->all();
        
        foreach($available['static'] as &$permission)
        {
            if(isset($checkedPermissions[$permission['id']]) && $checkedPermissions[$permission['id']] == 1)
                $permission['checked'] = true;
        }

        foreach($available['dynamic'] as &$resource)
        {
            foreach($resource['permissions'] as &$permission)
            if(isset($checkedPermissions[$permission['id']]) && $checkedPermissions[$permission['id']] == 1)
                $permission['checked'] = true;
        }
        
        return $available;
    }
    

    /**
     * Returns an array of all the available permission ID's that we can use.
     * 
     * @return array
     */
    public function allIds()
    {
        $ids = [];
        $flattened = array_dot($this->all());
        
        foreach($flattened as $key => $value)
        {
            if(strpos($key, '.id') !== false)
                $ids[] = $value;
        }
        
        return $ids;
    }
    
    public function allowedResourceIds($level, $resourceClass, $permissions)
    {
        $resourceAdmin = static::name($resourceClass, 'admin');

        if(isset($permissions[$resourceAdmin]) && $permissions[$resourceAdmin] == 1)
            return "all";

        $ids = [];
        foreach($permissions as $id => $value)
        {
            if(strpos($id, static::name($resourceClass, $level)) !== false && $value === 1)
                $ids[] = explode("_", $id)[2];
        }
        
        return $ids;
    }
    
    public static function name($resourceClass, $permission, $id = null)
    {
        $name = $resourceClass."_".$permission;

        // Don't need the ID if the permission type is admin
        if($permission == 'admin') return $name;
        
        $name = $id ? $name."_".$id : $name;
        
        return $name;
    }
    
    private function _getAvailablePermissions()
    {
        $permissions = [];
        
        foreach($this->raw['static'] as $permission)
        {
            // This is just a normal permission, so add it
            $permissions['static'][] = $permission;
        }

        $i = 0;
        foreach($this->raw['dynamic'] as $resource)
        {
            $resourceRepo = \App::make($resource['className']);
            $classPermissions = [];
            
            // We need to return a permission type for each item
            foreach($resourceRepo->all() as $item)
            {
                foreach($resource['permissions'] as $permission)
                {
                    $classPermissions[] = $this->_formatPermission($permission, $resource, $item);
                }
            }
            
            $permissions['dynamic'][$i]['name'] = $resource['niceName'];
            $permissions['dynamic'][$i]['permissions'] = $classPermissions;
            $i++;
        }
        
        
        return $permissions;
    }
    
    private function _formatPermission($permission, $resource, $item = null)
    {
        $id = $item['id'] ?: null;
        $permission['id'] = static::name($resource['className'], $permission['id'], $id);
        $permission['name'] = $this->_parseStringForModel($permission['name'], $item);
        $permission['description'] = $this->_parseStringForModel($permission['description'], $item);

        return $permission;
    }
    
    private function _extractColumnName($string)
    {
        $columns = [];
        preg_match_all('/{([^}]*)}/', $string, $columns);
        
        if(isset($columns[1]))
            return $columns[1];
    }
    
    private function _parseStringForModel($string, $model)
    {
        if($columns = $this->_extractColumnName($string))
        {
            foreach ($columns as $col)
            {
                $subject = "{" . $col . "}";
                $string  = str_replace($subject, $model[$col], $string);
            }
        }
        
        return $string;
    }
    

} 