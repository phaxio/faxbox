<?php namespace Faxbox\Repositories\Setting;

use Faxbox\Repositories\EloquentAbstractRepository;
use Faxbox\Setting;
use Illuminate\Config\Repository;

class LaravelConfigSettingRepository extends EloquentAbstractRepository implements SettingInterface
{

    protected $config;
    protected $model;

    public function __construct(Setting $setting, Repository $config)
    {
        $this->config = $config;
        $this->model = $setting;
    }
    
    public function get($keys, $forceEnvFile = false)
    {
        if(is_string($keys))
        {
            return $this->findKeyValue($keys, $forceEnvFile);
        }
        
        $result = [];
        foreach($keys as $key)
        {
            $result[$key] = $this->findKeyValue($key, $forceEnvFile);
        }
        
        return $result;
    }

    private function findKeyValue($key, $forceEnvFile)
    {
        // If this name exists in a config file, return that
        $setting = $this->config->get($key);
        if($setting !== null && !$forceEnvFile) 
            return $setting;
        elseif($forceEnvFile)
            return safe_getenv($key);
        else
            return $this->model->select('value')->where('name', '=', $key)->pluck('value');

    }

    public function write($key, $value, $forceEnvFile = false)
    {
        if(!$forceEnvFile) return $this->writeToDb($key, $value);

        list($namespace, $group, $item) = $this->config->parseKey($key);

        $path = base_path("userdata/.env.php");
        $content = '';

        if(file_exists($path))
            $content = file_get_contents($path);
        
        $array = eval('?>'.$content);
        
        // convert . to _ since some configs don't like . in env vars
        $key = str_replace('.', '_', $key);
        
        $array[$key] = $value;

        $string = "<?php\n\nreturn ".var_export($array, true).";";
        
        file_put_contents($path, $string);

        //put the value into the environment at run time
        $_ENV[$key] = $value;

        //reload the configuration with the changes to the environment
        \Config::reload($group, $namespace);
    }

    private function writeToDb($name, $value)
    {
        $setting = $this->model->newInstance();
        return $setting->updateOrCreate(['name' => $name], ['value' => $value]);

        // Lets instead only write existing values to db
//        $result = $this->model->where('name', '=', $name)->first();
//
//        if($result)
//        {
//            $result->value = $value;
//            return $result->save();
//        }


    }


    public function writeArray($keyValue, $forceEnvFile = false)
    {
        // sometimes passed in by forms
        unset($keyValue['_token']);
        unset($keyValue['_method']);
        
        $keyValue = array_dot($keyValue);
        
        foreach($keyValue as $key => $value)
        {
            $this->write($key, $value, $forceEnvFile);
        }
    }
}