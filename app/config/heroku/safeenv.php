<?php

function safe_getenv($name){
    return isset($_ENV[$name]) ? $_ENV[$name] : null;
}