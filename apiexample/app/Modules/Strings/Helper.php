<?php

namespace App\Modules\Strings;

class Helper
{
    /**
     * Extracts class name even with namespace.
     *
     * @param $namespace
     * @return mixed
     */
    public static function NamespaceToClassName($namespace)
    {
        $class = explode( '\\', $namespace);
        return end($class);
    }

    /**
     * Converts class name into table name.
     *
     * @param string $class_name
     * @param string $remove : String that must be removed from class name
     * @return string
     */
    public static function TableNameFromClassName($class_name, $remove = '')
    {
        if ($remove != '') {
            return strtolower(str_replace($remove, '', $class_name));
        } else {
            return strtolower($class_name);
        }
    }
}