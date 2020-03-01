<?php

/// Copyright (c) Vito Domenico Tagliente
/// Generic Application Command interface

namespace Pure;

abstract class Command
{
    /// Used to execute the command
    /// @param arguments - The array of parameters
    /// @return true if succeed
    public abstract function execute($arguments);

    /// Used to retrieve the command documentation
    /// @return - The documentation string
    public abstract function help();
}
