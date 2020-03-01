<?php

/// Copyright (c) Vito Domenico Tagliente
///
/// This utility is able to parse config files and return options.
/// It is possible to set a base path form which look at for config files.
/// When the get method is called, the option name parameter should be formatted using
/// the following syntax: filename_without_php.option_name
/// The system will look for the file: base_path/filename_without_php.php. If the file exists,
/// it will be loaded in memory with all of its options.
/// This will let the system to cache data and avoid multiple readings ffrom files.
///
/// --------------------------------------------------------------
/// Example:
/// Define a file located at: path/app/Config/app.php as following:
///
/// return array(
///		'one' => 1,
///		'key' => 'stringa'
///	);
///
/// The options can be retrieved as following:
///
/// $one = Pure\Config::get('app.one');
///	$key = Pure\Config::get('app.key');
///
/// Null is retrieved in cases in which options are not found.

namespace Pure;

class Config
{
    /// constructor
    private function __construct()
    {
    }

    /// destructor
    private function __destruct()
    {
    }

    /// base path from which looking for config files
    private static $s_basePath = null;

    /// cached configs data
    private static $s_configs = array();

    /// Both lets to set or retrieve the base path
    /// @param path - The new base path
    /// @return - The base path
    public static function path($path = null)
    {
        if (!empty($path))
        {
            self::$s_basePath = $path;
        }
        return self::$s_basePath;
    }

    /// Retrieve a config option
    /// @param name - The name of the option
    /// @param path - The path form wich looking for the config file, if null the base path will be used
    /// @return - The option if found. Null if not.
    public static function get($name, $path = null)
    {
        $parts = explode('.', $name);
        // Does the option_name contain the dot?
        if (count($parts) > 1)
        {
            if (empty($path))
            {
                // start looking from the base path
                $path = self::$s_basePath;
            }

            // split the string by the dot
            $config_name = $parts[0];
            if (!(strpos($config_name, '/') !== false))
            {
                // it is a relative path, add the base path to the config name
                $config_name = $path . '/' . $config_name;
            }

            // if the file is not cached, load the file
            if (!array_key_exists($config_name, self::$s_configs))
            {
                // check if the file exists
                $filename = $config_name . '.php';
                if (file_exists($filename))
                {
                    $file_configs = include($filename);
                    if (isset($file_configs) && is_array($file_configs))
                    {
                        self::$s_configs[$config_name] = $file_configs;
                    }
                }
            }

            // return the option
            if (array_key_exists($parts[1], self::$s_configs[$config_name]))
            {
                return self::$s_configs[$config_name][$parts[1]];
            }
        }
        return null;
    }
}
