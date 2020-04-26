<?php

namespace Webasics\Framework\Helper;

/**
 * Class ArrayHelper
 * @package Webasics\Framework\Helper
 */
class ArrayHelper
{

    public static function arrayToObject(array $arr, string $className = '')
    {
        $keys = [];
        $reflection = new \ReflectionClass($className);
        foreach ($reflection->getConstructor()->getParameters() as $parameter) {

            $defaultValue = null;

            if ($parameter->isDefaultValueAvailable()) {
                $defaultValue = $parameter->getDefaultValue();
            }

            $keys[] = $arr[$parameter->getName()] ?? $defaultValue;
        }

        return new $className(...$keys);
    }
}