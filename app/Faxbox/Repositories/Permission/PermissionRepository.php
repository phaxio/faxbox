<?php namespace Faxbox\Repositories\Permission;

use \Cartalyst\Sentry\Users\UserInterface;
Use Config;
use Faxbox\Repositories\Phone\PhoneInterface;

class PermissionRepository implements PermissionInterface, PermissionInterface {

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

    public function allWithChecked($checkedPermissions, $default = -1)
    {
        $available = $this->all();

        foreach ($available['static'] as &$permission)
        {
            $permission['value'] = $default;
            if (isset($checkedPermissions[$permission['id']]))
                $permission['value'] = $checkedPermissions[$permission['id']];
        }

        foreach ($available['dynamic'] as &$resource)
        {
            foreach ($resource['permissions'] as &$permission)
            {
                $permission['value'] = $default;
                if (isset($checkedPermissions[$permission['id']]))
                    $permission['value'] = $checkedPermissions[$permission['id']];
            }
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
        $ids       = [];
        $flattened = array_dot($this->all());

        foreach ($flattened as $key => $value)
        {
            if (strpos($key, '.id') !== false)
                $ids[] = $value;
        }

        return $ids;
    }

    /**
     * @param string $level
     * @param string $resourceClass
     * @param array  $permissions
     *
     * @return array An array of accessible ID's
     */
    public function allowedResourceIds($level, $resourceClass, $permissions)
    {
        $ids = [];
        foreach ($permissions as $id => $value)
        {
            $admin      = static::name($resourceClass, 'admin');
            $permission = static::name($resourceClass, $level);

            if ( (strpos($id, $permission) !== false && $value === 1) ||
                 (strpos($id, $admin) !== false && $value === 1)
            ) $ids[] = explode("_", $id)[2];
        }

        return $ids;
    }

    /**
     * @param      $resourceClass
     * @param      $permission
     * @param null $id
     *
     * @return string
     */
    public static function name($resourceClass, $permission, $id = null)
    {
        $name = $resourceClass . "_" . $permission;

        $name = $id ? $name . "_" . $id : $name;

        return $name;
    }

    private function _getAvailablePermissions()
    {
        $permissions = [];

        foreach ($this->raw['static'] as $permission)
        {
            // This is just a normal permission, so add it
            $permissions['static'][] = $permission;
        }

        $i = 0;
        foreach ($this->raw['dynamic'] as $resource)
        {
            $resourceRepo     = \App::make($resource['className']);
            $classPermissions = [];

            // We need to return a permission type for each item
            foreach ($resourceRepo->all() as $item)
            {
                foreach ($resource['permissions'] as $permission)
                {
                    $classPermissions[] = $this->_formatPermission($permission,
                        $resource,
                        $item);
                }
            }

            $permissions['dynamic'][$i]['name']        = $resource['niceName'];
            $permissions['dynamic'][$i]['permissions'] = $classPermissions;
            $i++;
        }


        return $permissions;
    }

    private function _formatPermission($permission, $resource, $item = null)
    {
        $id                        = $item['id'] ?: null;
        $permission['id']          = static::name($resource['className'],
            $permission['id'],
            $id);
        $permission['name']        = $this->_parseStringForModel($permission['name'],
            $item);
        $permission['description'] = $this->_parseStringForModel($permission['description'],
            $item);

        return $permission;
    }

    private function _extractColumnName($string)
    {
        $columns = [];
        preg_match_all('/{([^}]*)}/', $string, $columns);

        if (isset($columns[1]))
            return $columns[1];
    }

    private function _parseStringForModel($string, $model)
    {
        if ($columns = $this->_extractColumnName($string))
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