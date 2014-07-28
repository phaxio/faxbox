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
        
        $this->available = $this->all();
    }
    
    public function all()
    {
        return $this->_getAvailablePermissions();
    }

    /**
     * Returns an array of all the available permission ID's that we can use.
     * 
     * @return array
     */
    public function allIds()
    {
        $ids = [];
        $flattened = array_dot($this->available);
        
        foreach($flattened as $key => $value)
        {
            if(strpos($key, '.id') !== false)
                $ids[] = $value;
        }
        
        return $ids;
    }
    
    public function resource($resourceClass)
    {
        
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
        
        foreach($this->raw['staticPermissions'] as $permission)
        {
            // This is just a normal permission, so add it
            $permissions['static'][] = $permission;
        }

        $i = 0;
        foreach($this->raw['dynamicPermissions'] as $resource)
        {
            $resourceRepo = \App::make($resource['className']);
            $classPermissions = [];
            
            // We need to return a permission type for each item
            foreach($resourceRepo->all() as $item)
            {
                foreach($resource['itemLevelPermissions'] as $permission)
                {
                    $classPermissions['itemLevel'][] = $this->_formatPermission($permission, $resource, $item);
                }
            }

            foreach($resource['classLevelPermissions'] as $permission)
            {
                $classPermissions['classLevel'][] = $this->_formatPermission($permission, $resource, $item);
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