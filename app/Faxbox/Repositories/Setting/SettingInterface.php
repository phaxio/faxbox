<?php namespace Faxbox\Repositories\Setting;

interface SettingInterface
{

    public function get( $keys, $forceEnvFile = false );

    public function write( $key, $value, $forceEnvFile = false );

    public function writeArray( $keyValue, $forceEnvFile = false );
}