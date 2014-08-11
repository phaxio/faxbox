<?php namespace Faxbox\Repositories\Setting;

use Faxbox\Repositories\EloquentAbstractRepository;
use Faxbox\Setting;
use October\Rain\Config\Repository;

class EloquentSettingRepository extends EloquentAbstractRepository implements SettingInterface {

    /**
     * Construct a new SentryUser Object
     */
    public function __construct(Setting $setting, Repository $config)
    {
        $this->model = $setting;
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
    
    private function findKeyValue($key, $forceDb)
    {
        // If this name exists in a config file, return that
        $setting = $this->config->get($key);
        if($setting && !$forceDb) return $setting;

        // otherwise  we'll check the db
        $result = $this->model->select('value')->where('name', '=', $key)->lists('value');

        if(!$result) return null;

        return $result[0];
    }

    public function write($key, $value, $forceDb = false)
    {
        if($forceDb) return $this->writeToDb($key, $value);
        
        // If writing to a config file fails (key doesn't exist), we'll write it to the DB
        try{
            
            return $this->config->write($key, $value);
            
        } catch (\Exception $e) 
        {
            return $this->writeToDb($key, $value);
        }
        
    }
    
    public function writeArray($keyValue, $forceDb = false)
    {
        $keyValue = array_dot($keyValue);
        
        foreach($keyValue as $key => $value)
        {
            $this->write($key, $value, $forceDb);
        }
    }
    
    private function writeToDb($name, $value)
    {
        //$setting = $this->model->newInstance();
        //return $setting->updateOrCreate(['name' => $name], ['value' => $value]);
        
        // Lets instead only write existing values to db
        $result = $this->model->where('name', '=', $name)->first();
        
        if($result)
        {
            $result->value = $value;
            return $result->save();
        }
        
    }
    
    
}