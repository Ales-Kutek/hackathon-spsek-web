<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 8/8/17
 * Time: 2:45 PM
 */

namespace UW\Core\App\Helper;


use Nette\StaticClass;

class Strings
{
    use StaticClass;

    public static function camelCase(string $entry, $delimiter = "_")
    {
        return str_replace($delimiter, '', ucwords($entry, $delimiter));
    }

    public static function flatten($array, $prefix = '') {
        $result = array();
        foreach($array as $key=>$value) {
            if(is_array($value)) {
                $result = $result + self::flatten($value, $prefix . $key . '.');
            }
            else {
                $result[$prefix . $key] = $value;
            }
        }
        return $result;
    }
}