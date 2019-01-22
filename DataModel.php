<?php

/*
	Questa classe permette la rappresentazione generale
	di modelli di dati che possono essere agilmente manipolati 
	e trasformati in rappresentazioni equivalenti
*/

namespace Pure;

class DataModel
{
	// insieme dei campi della tabella
	private $properties = array();

	public function __get($key){
        if(array_key_exists($key, $this->properties))
		  return $this->properties[$key];
        return null;
	}

	public function __set( $key, $value){
		$this->properties[$key] = $value;
	}

    public function __isset($key){
        return isset($this->properties[$key]);
    }

    public function keys(){
    	return array_keys($this->properties);
    }

    // ritorna il modello in formato array associativo
    public function data(){
    	return $this->properties;
    }

    // ritorna la codifica json del modello
    public function json(){
    	return json_encode($this->properties);
    }

    // ritorna il modello in formato url GET
    public function url(){
    	return http_build_query($this->properties);
    }
}

?>