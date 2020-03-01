<?php

/// Copyright (c) Vito Domenico Tagliente
/// Generic controller interface

namespace Pure;

abstract class Controller
{
    /// singleton pattern
    private static $s_instance = null;

    /// constructor
    public function __construct()
    {
        self::$s_instance = $this;
    }

    /// destructor
    public function __destruct()
    {
        self::$s_instance = null;
    }

    /// singleton pattern
    /// @return - The singleton of the class
    public static function main()
    {
        return self::$s_instance;
    }

    /// Retrieve the default action for this controller
    /// @return - The action
    public static function action()
    {
        return strtolower(static::name());
    }

    /// Retrieve the name of the Controller
    /// @return - The name of the Controller without the 'Controller' part
    public static function name()
    {
        $path = explode('\\', get_called_class());
        $controller_name = array_pop($path);
        return trim(str_replace("Controller", "", $controller_name));
    }

    /// Retrieve the base url for this controller
    /// @param - The base url
    public static function url()
    {
        return base_url(static::action());
    }
}
