<?php

/*
    Questa classe permette di eseguire dei controlli di consistenza
    di contenuti. In particolare, è da utilizzare per ripulire le variabili
    passate tra front-end e back-end, evitando query injection.
*/

namespace Pure;

class Sanitize {
    private function __construct(){}

    public static function integer($input){
    	return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function number($input){
    	return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT);
    }

    public static function text($input){
    	return filter_var($input, FILTER_SANITIZE_STRING);
    }

    public static function email($input){
    	return filter_var($input, FILTER_SANITIZE_EMAIL);
    }

    public static function url($input){
    	return filter_var($input, FILTER_SANITIZE_URL);
    }

    private function __destruct(){}
}

?>
