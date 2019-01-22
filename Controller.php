<?php

/*
    Interfaccia standard per i Controller
*/

namespace Pure;

abstract class Controller {

	public static function action(){
        $path = explode('\\', get_called_class());
        $controller_name = array_pop($path);
        $controller_name = str_replace("Controller", "", $controller_name);
        return strtolower(trim($controller_name));        
    }

    public static function name(){
        return ucfirst(static::action());
    }

    public static function url(){
    	return base_url(static::action());
    }

    // singleton pattern

	private static $instance = null;

	public function __construct(){
		self::$instance = $this;
	}

	public function __destruct(){
		self::$instance = null;
	}	

	public static function main(){ return self::$instance; }

}

?>
