<?php

/*
	API di autenticazione
	Questa interfaccia permette di eseguire le operazioni di autenticazione.
	Di base richiede che sia specificata la classe del modello dell'utente:
	Auth::$class_name = 'App\Models\User' per esempio
	Specificato il modello, è possibile eseguire l'autenticazione
	Per esempio:
	$authenticated = Auth::authenticate("username = '.' AND password = '**'");
*/

namespace Pure;

class Auth
{
	private function __construct(){}
	private function __destruct(){}

	// authentication model class_name
	public static $class_name = null;
	// define the key used for model serialization
	private static $session_key = 'user';

	// returns true if the user is logged in
	public static function check(){
		return Session::exists(self::$session_key);
	}

	// returns the user model
	public static function user(){
		return Session::get(self::$session_key);
	}

	// performs the authentication operation
	public static function authenticate($condition, $remember = false){
		// check if the user model class is specified
		if(!empty(self::$class_name) && class_exists(self::$class_name))
		{
			// UserModelClass:find($condition)
			// call the find function using the given conditions
			$user = call_user_func(self::$class_name . '::find', $condition);
			if($user)
			{
				// if an user is found, serialize it and push into the session
				Session::set(self::$session_key, $user);
				return true;
			}
		}
		return false;
	}

	// performs the logout operation
	public static function logout(){
		Session::erase(self::$session_key);
	}
}

?>
