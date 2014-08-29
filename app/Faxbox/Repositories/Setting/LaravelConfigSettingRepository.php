<?php namespace Faxbox\Repositories\Setting;

use Faxbox\Repositories\EloquentAbstractRepository;
use Illuminate\Config\Repository;

class LaravelConfigSettingRepository extends EloquentAbstractRepository implements SettingInterface {

    protected $config;
    
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }
    
    public function get($keys)
    {
        if(is_string($keys)){
            return $this->findKeyValue($keys);
        }
        
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

        $path = base_path("userdata/.env.php");
        $content = '';

        if(file_exists($path))
            $content = file_get_contents($path);
        
        $array = eval('?>'.$content);
        $array[$key] = $value;

        $string = "<?php\n\nreturn ".var_export($array, true).";";
        
        file_put_contents($path, $string);

        //put the value into the environment at run time
        $_ENV[$key] = $value;

        //reload the configuration with the changes to the environment
        \App::getConfigLoader()->load(\App::environment(), $group);
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