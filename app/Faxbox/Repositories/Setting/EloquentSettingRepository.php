<?php namespace Faxbox\Repositories\Setting;

use Faxbox\Repositories\EloquentAbstractRepository;
use October\Rain\Config\Repository;

class EloquentSettingRepository extends EloquentAbstractRepository implements SettingInterface {

    protected $config;
    
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }
    
    public function get($keys, $forceDb = false)
    {
        if(is_string($keys)) return $this->findKeyValue($keys, $forceDb);
        
        $result = [];
        foreach($keys as $key)
        {
            $result[$key] = $this->findKeyValue($key, $forceDb);
        }
        
        return $result;
    }
    
    private function findKeyValue($key)
    {
        return $this->config->get($key);
    }

    public function write($key, $value)
    {
        $this->config->write($key, $value);
    }

    public function writeArray($keyValue, $forceDb = false)
    {
        $keyValue = array_dot($keyValue);
        
        foreach($keyValue as $key => $value)
        {
            $this->write($key, $value, $forceDb);
        }
    }
}