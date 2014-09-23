<?php namespace Faxbox\Repositories\Setting;

use Illuminate\Config\Repository;

class ConfigRepository extends Repository
{
    /**
     * Reloads the configuration group for the key.
     *
     * @param  string  $group
     * @param  string  $namespace
     * @param  string  $collection
     * @return void
     */
    public function reload($group, $namespace)
    {
        $env = $this->environment;

        $collection = $this->getCollection($group, $namespace);

        $items = $this->loader->load($env, $group, $namespace);

        $this->items[$collection] = $items;
    }
}