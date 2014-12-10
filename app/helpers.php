<?php
if( ! function_exists('safe_getenv'))
{
    function safe_getenv($name)
    {
        $name = str_replace('.', '_', $name);
        return isset($_ENV[$name]) ? $_ENV[$name] : null;
    }
}

if( ! function_exists('convertPHPSizeToBytes'))
{
//This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)  
    function convertPHPSizeToBytes($sSize)
    {
        if (is_numeric($sSize))
        {
            return $sSize;
        }
        $sSuffix = substr($sSize, -1);
        $iValue  = substr($sSize, 0, -1);
        switch (strtoupper($sSuffix))
        {
            case 'P':
                $iValue *= 1024;
            case 'T':
                $iValue *= 1024;
            case 'G':
                $iValue *= 1024;
            case 'M':
                $iValue *= 1024;
            case 'K':
                $iValue *= 1024;
                break;
        }

        return $iValue;
    }
}

if( ! function_exists('getMaximumFileUploadSize'))
{
    function getMaximumFileUploadSize()
    {
        return min(convertPHPSizeToBytes(ini_get('post_max_size')),
            convertPHPSizeToBytes(ini_get('upload_max_filesize')));
    }
}

if( ! function_exists('cleanPhone'))
{
    function cleanPhone($number)
    {
        $startsWithPlus = substr($number, 0, 1) === '+';
        $number         = preg_replace('/[^\d]/', '', $number);

        if ($startsWithPlus)
        {
            $number = '+' . $number;
        } else if (strlen($number) == 10)
        {
            $number = '+1' . $number;
        } else
        {
            $number = '+' . $number;
        }

        return $number;
    }
}

if( ! function_exists('isUsingLocalStorage'))
{
    function isUsingLocalStorage()
    {
        if (!isset($_ENV['USE_LOCAL_STORAGE'])) return true;

        if(!$_ENV['USE_LOCAL_STORAGE'] || $_ENV['USE_LOCAL_STORAGE'] === 'false')
            return false;

        return true;
    }
}

if (!function_exists('array_column')) {

    /**
     * Returns the values from a single column of the input array, identified by
     * the $columnKey.
     *
     * Optionally, you may provide an $indexKey to index the values in the returned
     * array by the values from the $indexKey column in the input array.
     *
     * @param array $input A multi-dimensional array (record set) from which to pull
     *                     a column of values.
     * @param mixed $columnKey The column of values to return. This value may be the
     *                         integer key of the column you wish to retrieve, or it
     *                         may be the string key name for an associative array.
     * @param mixed $indexKey (Optional.) The column to use as the index/keys for
     *                        the returned array. This value may be the integer key
     *                        of the column, or it may be the string key name.
     * @return array
     */
    function array_column($input = null, $columnKey = null, $indexKey = null)
    {
        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        $argc = func_num_args();
        $params = func_get_args();

        if ($argc < 2) {
            trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
            return null;
        }

        if (!is_array($params[0])) {
            trigger_error('array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given', E_USER_WARNING);
            return null;
        }

        if (!is_int($params[1])
            && !is_float($params[1])
            && !is_string($params[1])
            && $params[1] !== null
            && !(is_object($params[1]) && method_exists($params[1], '__toString'))
        ) {
            trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
            return false;
        }

        if (isset($params[2])
            && !is_int($params[2])
            && !is_float($params[2])
            && !is_string($params[2])
            && !(is_object($params[2]) && method_exists($params[2], '__toString'))
        ) {
            trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
            return false;
        }

        $paramsInput = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;

        $paramsIndexKey = null;
        if (isset($params[2])) {
            if (is_float($params[2]) || is_int($params[2])) {
                $paramsIndexKey = (int) $params[2];
            } else {
                $paramsIndexKey = (string) $params[2];
            }
        }

        $resultArray = array();

        foreach ($paramsInput as $row) {

            $key = $value = null;
            $keySet = $valueSet = false;

            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key = (string) $row[$paramsIndexKey];
            }

            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value = $row[$paramsColumnKey];
            }

            if ($valueSet) {
                if ($keySet) {
                    $resultArray[$key] = $value;
                } else {
                    $resultArray[] = $value;
                }
            }

        }

        return $resultArray;
    }

}