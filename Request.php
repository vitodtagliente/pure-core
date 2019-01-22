<?php

/*
    Questa classe permette l'accesso alle variabili di richiesta.
    - Utilizzare la funzione get per esaminare le variabili GET
    - Utilizzare la funzione post per esaminare le variabili POST
    - Utilizzare la funzione input per esaminare in automatico le variabili
      discriminando la modalità.
*/

namespace Pure;

class Request {
    private function __construct(){}

    // serach for POST variable
    public static function post($key){
        if(!empty($_POST) && isset($_POST[ $key ]))
                return $_POST[$key];
        else return null;
    }

    // search for GET variable
    public static function get($key){
        if(!empty($_GET) && isset($_GET[ $key ]))
                return $_GET[$key];
        else return null;
    }

    // search for GET or POST variable automatically
    public static function input($key){
      $method = $_SERVER['REQUEST_METHOD'];
      if($method == 'GET')
        return self::get($key);
      else if($method == 'POST')
        return self::post($key);
      else return null;
    }

    private function __destruct(){}
}

?>
