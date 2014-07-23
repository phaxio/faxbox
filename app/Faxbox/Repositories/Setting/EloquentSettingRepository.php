<?php namespace Faxbox\Repositories\Setting;

use Faxbox\Repositories\EloquentAbstractRepository;
use Faxbox\Setting;

class EloquentSettingRepository extends EloquentAbstractRepository implements SettingInterface {

    /**
     * Construct a new SentryUser Object
     */
    public function __construct(Setting $setting)
    {
        $this->model = $setting;
    }
    
}