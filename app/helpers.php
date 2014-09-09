<?php
if( ! function_exists('safe_getenv'))
{
    function safe_getenv($name)
    {
        return isset($_ENV[$name]) ? $_ENV[$name] : null;
    }
}