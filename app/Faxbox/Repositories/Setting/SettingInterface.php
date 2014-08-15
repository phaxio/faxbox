<?php namespace Faxbox\Repositories\Setting;

interface SettingInterface {

    public function get($keys, $forceDb = false);

    public function write($key, $value);

    public function writeArray($keyValue, $forceDb = false);
}