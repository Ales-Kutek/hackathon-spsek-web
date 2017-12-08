<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 8/1/17
 * Time: 7:18 AM
 */

namespace UW\Core\App\Helper;


use Nette\StaticClass;

class ArrayMapper
{
    use StaticClass;

    /**
     * Projde pole, a pomocí callbacků nastaví hodnotu a popřípadě klíč
     * @param $iterable
     * @param callable $valueClb
     * @param callable|NULL $keyClb
     * @return array
     */
    public static function simpleWalk($iterable, callable $valueClb, callable $keyClb = NULL): array
    {
        $result = array();

        foreach ($iterable as $key => $value) {
            if ($keyClb !== NULL) {
                $key = $keyClb($key, $value);
            }

            $newValue = $valueClb($key, $value);

            $result[$key] = $newValue;
        }

        return $result;
    }

    public static function simpleWalkToString($iterable, callable $valueClb): string
    {
        $result = "";

        foreach ($iterable as $key => $value) {
            $newValue = $valueClb($key, $value);

            $result .= $newValue;
        }

        return $result;
    }

    /**
     * @param $iterable
     * @param callable $callback
     * @return null
     */
    public static function simpleWalkResult($iterable, callable $callback)
    {
        $result = NULL;

        foreach ($iterable as $key => $value) {
            $result = $callback($key, $value);

            if ($result !== NULL) {
                return $result;
            }
        }

        return $result;
    }
}