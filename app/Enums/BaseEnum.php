<?php
/**
 * @author Gizem Sever <gizemsever68@gmail.com>
 */

namespace App\Enums;

use ReflectionClass;
use ReflectionException;

abstract class BaseEnum
{
    /**
     * @return array
     * @throws ReflectionException
     */
    public static function getKeys()
    {
        $class = self::calledClass();
        return array_keys($class->getConstants());
    }

    /**
     * @return ReflectionClass
     * @throws ReflectionException
     */
    private static function calledClass()
    {
        return new ReflectionClass(get_called_class());
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function getValues()
    {
        $class = self::calledClass();
        return array_values($class->getConstants());
    }
}
