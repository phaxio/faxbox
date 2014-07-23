<?php namespace Faxbox\Repositories\Permission;

Use Config;
use Faxbox\Repositories\Phone\PhoneInterface;

class PermissionRepository implements PermissionInterface{

    /**
     * @var The raw permissions config array
     */
    protected $raw;
    
    /**
     * @var array avaiable permissions taken from the config
     */
    protected $available;

    /**
     * @var PhoneInterface The phone repository
     */
    protected $phones;
    
    /**
     * Construct a new SentryUser Object
     */
    public function __construct(Config $config, PhoneInterface $phone)
    {
        $this->raw = Config::get('faxbox.permissions');
        
        // We need this to generate our dynamic permissions
        $this->phones = $phone;
        
//        $this->available = $this->all();
    }
    
    public function all()
    {
        return $this->_getFormatted();
    }
    
    private function _getFormatted()
    {
        $formattedPermissions = [];
        
        foreach($this->raw as $permission)
        {
            if(strpos($permission['name'], 'phone_') !== false)
            {
                // We need to make a permission type for each phone number
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