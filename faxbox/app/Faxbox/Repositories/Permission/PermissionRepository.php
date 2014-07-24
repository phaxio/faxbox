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

    /**
     * The phone repository
     * 
     * @var PhoneInterface 
     */
    protected $phones;
    
    public function __construct(Config $config, PhoneInterface $phone)
    {
        $this->raw = Config::get('faxbox.permissions');
        
        // We need this to generate our dynamic permissions
        $this->phones = $phone;
        
        $this->available = $this->all();
    }
    
    public function all()
    {
        return $this->_getFormatted();
    }
    
    public function allowedForPhone($permission, $number, UserInterface $user)
    {
        $phonePermission = $this->_makePhonePermissionName($permission, $number);
        
        return $user->hasPermission($phonePermission);
    }
    
    private function _makePhonePermissionName($permission, $number)
    {
        return "phone_".$permission."_".$number;
    }
    
    private function _getFormatted()
    {
        $formattedPermissions = [];
        
        foreach($this->raw as $permission)
        {
            if(strpos($permission['name'], 'phone_') !== false)
            {
                // We need to return a permission type for each phone number
                foreach($this->phones->all() as $phone)
                {
                    $id = sprintf($permission['name'], $phone['number']);
                    $formattedPermissions[$id]['short'] = sprintf($permission['short'], $phone['number']);
                    $formattedPermissions[$id]['description'] = sprintf($permission['description'], $phone['number']);
                }
            } else {
                // This is just a normal permission, so add it
                $id = $permission['name'];
                $formattedPermissions[$id]['short'] = $permission['short'];
                $formattedPermissions[$id]['description'] = $permission['description'];
            }
        }
        
        return $formattedPermissions;
    }
    

} 