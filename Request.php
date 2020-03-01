<?php

/// Copyright (c) Vito Domenico Tagliente
/// Collection of utilities used to easily retrieve Request data

namespace Pure;

class Request
{
    /// constructor
    private function __construct()
    {
    }

    /// destructor
    private function __destruct()
    {
    }

    /// Retrieve  a POST variable
    /// @param key - the variabile to look at
    /// @return - the variable if found with success.
    public static function post($key)
    {
        if (!empty($_POST) && isset($_POST[$key]))
        {
            return $_POST[$key];
        }
        return null;
    }

    /// Retrieve a GET variable
    /// param @key - The variable looking at
    /// @return - the variable if found with success.
    public static function get($key)
    {
        if (!empty($_GET) && isset($_GET[$key]))
        {
            return $_GET[$key];
        }
        return null;
    }

    /// Retrieve a request variable
    /// The server will handle the request method to identify
    /// if the variable is in the Get or Post collections
    /// param @key - The variable looking at
    /// @return - the variable if found with success.
    public static function input($key)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'GET')
        {
            return self::get($key);
        }
        else if ($method == 'POST')
        {
            return self::post($key);
        }
        return null;
    }

    /// Check if a request variable exists
    /// @param - the variable looking at
    /// @return - true if found
    public static function exists($key)
    {
        $data = self::input($key);
        return isset($data);
    }
}
