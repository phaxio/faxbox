<?php namespace Faxbox\Repositories\Setting;

interface SettingInterface {

    public function get($keys);

    public function write($key, $value);

    public function writeArray($keyValue);
}