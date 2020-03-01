<?php

/// Copyright (c) Vito Domenico Tagliente
/// Utility class used to prevent query injections.
/// Used to clear data that comes from the front-end

namespace Pure;

class Sanitize
{
    /// constructor
    private function __construct()
    {
    }

    /// destructor
    private function __destruct()
    {
    }

    /// Filter an integer type data
    /// @param input - The data to be cleared
    /// @return - The filtered value
    public static function integer($input)
    {
        return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    }

    /// Filter a number type data
    /// @param input - The data to be cleared
    /// @return - The filtered value
    public static function number($input)
    {
        return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT);
    }

    /// Filter a text type data
    /// @param input - The data to be cleared
    /// @return - The filtered value
    public static function text($input)
    {
        return filter_var($input, FILTER_SANITIZE_STRING);
    }

    /// Filter an email type data
    /// @param input - The data to be cleared
    /// @return - The filtered value
    public static function email($input)
    {
        return filter_var($input, FILTER_SANITIZE_EMAIL);
    }

    /// Filter an URL type data
    /// @param input - The data to be cleared
    /// @return - The filtered value
    public static function url($input)
    {
        return filter_var($input, FILTER_SANITIZE_URL);
    }
}
