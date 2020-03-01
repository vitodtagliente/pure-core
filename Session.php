<?php

/// Copyright (c) Vito Domenico Tagliente
/// Session manager

namespace Pure;

class Session
{
	/// application's defined session security string
	/// must be different per application
	private static $s_sessionString;

	/// constructor
    private function __construct()
    {

    }

    /// destructor
    private function __destruct()
    {

    }

    /// Starts the session and set the session string
	/// @param $security_string - The string that will be used to identity the application's sessions
	static public function start($security_string = "pure.session.")
	{
		session_start();
		self::$s_sessionString = $security_string;
	}

	/// Set a session's variable
	/// @param key - The name of the session's variable
	/// @param value - The new value
	static public function set($key, $value)
	{
		$key = self::$s_sessionString . $key;
		$obj = $value;
		$obj = serialize($value);
		$_SESSION[$key] = $obj;
	}

	/// Retrieve a session's variable
	/// @param key - The name of the variable
	/// @return The variable if found
	static public function get($key)
	{
		$key = self::$s_sessionString . $key;
		if (isset($_SESSION[$key]))
		{
			$obj = $_SESSION[$key];
			return $obj = unserialize($_SESSION[$key]);
		}
		else return null;
	}

	/// Check if a variable is already stored in the session
	/// @param key - the name of the variable
	/// @return true if exists
	static public function exists($key)
	{
		$key = self::$s_sessionString . $key;
		return (isset($_SESSION[$key]));
	}

	/// remove a variable from the session
	/// @param key - The name of the variable to be removed
	static public function erase($key)
	{
		$key = self::$s_sessionString . $key;
		if (isset($_SESSION[$key]))
		{
			unset($_SESSION[$key]);
		}
	}

	/// Clear all the stored variabled from the session
	static public function clear()
	{
		session_unset();
	}

	/// Close the session
	static public function close()
	{
		session_destroy();
	}
}