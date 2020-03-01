<?php

/// Copyright (c) Vito Domenico Tagliente
/// Generica data model definition
/// Used to define veratile data that can be formatted in different output formats
/// such as json or URL

namespace Pure;

class DataModel
{
    /// The properties of the model
    private $properties = array();

    /// constructor
    /// @param args - The array or properties
    public function __construct(array $args = array())
    {
        // check if the array is an associative one
        if (array_keys($args) !== range(0, count($args) - 1)) {
            $this->properties = $args;
        }
    }

    /// Retrieve a property of the model
    /// @param name - The name of the property
    /// @return - The property is exists
    public function __get($name)
    {
        if (array_key_exists($name, $this->properties))
        {
            return $this->properties[$name];
        }
        return null;
    }

    /// Set a property of the model
    /// @param name - The name of the property
    /// @param value - The value of the property
    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    /// Implementation for the isset standard.
    /// Check if a property exists.
    /// @param name - the name of the property
    /// @return - true if the property exists.
    public function __isset($name)
    {
        return isset($this->properties[$name]);
    }

    /// Retrieve the properties names
    /// @return - The properties name's list
    public function keys()
    {
        return array_keys($this->properties);
    }

    /// Retrieve the array representation
    /// @return - the model in associative array format
    public function toArray()
    {
        return $this->properties;
    }

    /// Retrieve the json representation
    /// @return - The model in json format
    public function toJson()
    {
        return json_encode($this->properties);
    }

    /// Retrieve the URL representation
    /// @return - The model in URL format
    public function toURL()
    {
        return http_build_query($this->properties);
    }
}
