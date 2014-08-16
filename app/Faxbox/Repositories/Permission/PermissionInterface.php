<?php namespace Faxbox\Repositories\Permission;

interface PermissionInterface {

    public function all();

    public function allWithChecked($checkedPermissions = [], $default = -1);

    /**
     * Returns an array of all the available permission ID's that we can use.
     *
     * @return array
     */
    public function allIds();

    /**
     * @param string $level
     * @param string $resourceClass
     * @param array  $permissions
     *
     * @return array An array of accessible ID's
     */
    public function allowedResourceIds($level, $resourceClass, $permissions);

    /**
     * @param      $resourceClass
     * @param      $permission
     * @param null $id
     *
     * @return string
     */
    public static function name($resourceClass, $permission, $id = null);
}