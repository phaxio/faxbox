<?php namespace Faxbox\Repositories\Setting;

use Faxbox\Repositories\EloquentAbstractRepository;
//use October\Rain\Config\Repository;
use Illuminate\Config\Repository;

class LaravelConfigSettingRepository extends EloquentAbstractRepository implements SettingInterface {

    protected $config;
    
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }
    
    public function get($keys)
    {
        if(is_string($keys)) return $this->findKeyValue($keys);
        
        $result = [];
        foreach($keys as $key)
        {
            $result[$key] = $this->findKeyValue($key);
        }
        
        return $result;
    }
    
    private function findKeyValue($key)
    {
        return $this->config->get($key);
    }

    public function write($key, $value)
    {
        list($namespace, $group, $item) = $this->config->parseKey($key);

        $path = app_path("config/".\App::environment()."/$group.php");
        $content = '';

        if(file_exists($path))
            $content = file_get_contents($path);
        
        $array = eval('?>'.$content);
        
        array_set($array, $item, $value);
        
        $string = "<?php\n\nreturn ".var_export($array, true).";";
        
        file_put_contents($path, $string);
        
        // todo force refresh for config
    }

    public function writeArray($keyValue)
    {
        // sometimes passed in by forms
        unset($keyValue['_token']);
        unset($keyValue['_method']);
        
        $keyValue = array_dot($keyValue);
        
        foreach($keyValue as $key => $value)
        {
            $this->write($key, $value);
        }
    }
}