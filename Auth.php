<?php

/// Copyright (c) Vito Domenico Tagliente
/// Authentication utility
///
/// This class is used to authenticate the user
/// the user model is stored in the session
/// In order to let the user to login, it is required to define the User's model of data
/// such as: Auth::model('App\Models\User');
///
/// Once the data model is defined, is it possible to perform the authentication
/// as following:
/// $authenticated = Auth::authenticate("username = 'USERNAME' AND password = '****');

namespace Pure;

class Auth
{
    /// constructor
    private function __construct()
    {
    }

    /// destructor
    private function __destruct()
    {
    }

    /// The class name of the model used to represent the user
    private static $model_name = null;

    /// define the key used for model serialization
    private static $session_key = 'user';

    /// Check if the user is logged in
    /// @return - True if logged in
    public static function check()
    {
        return Session::exists(self::$session_key);
    }

    /// Retrieve the user model if logged in
    /// @return - The user model
    public static function user()
    {
        return Session::get(self::$session_key);
    }

    /// Set or retrieve the User model class
    /// @param model_name - The name of the User Model class
    /// @return - The name of the User model class
    public static function model($class_name = null)
    {
        if (isset($class_name))
        {
            self::$model_name = $class_name;
        }
        return self::$model_name;
    }

    /// Perform the user authentication
    /// @param condition - The authentication condition
    /// @param remeber - If should rememeber
    /// @return- True is the authentication succeed
    public static function authenticate($condition, $remember = false)
    {
        // check if the user model class is specified
        if (!empty(self::$model_name) && class_exists(self::$model_name))
        {
            // UserModelClass:find($condition)
            // call the find function using the given conditions
            $user = call_user_func(self::$model_name . '::find', $condition);
            if ($user)
            {
                // if an user is found, serialize it and push into the session
                Session::set(self::$session_key, $user);
                return true;
            }
        }
        return false;
    }

    /// Performs the logout operation
    public static function logout()
    {
        Session::erase(self::$session_key);
    }
}
