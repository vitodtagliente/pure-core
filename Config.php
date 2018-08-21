<?php

/*
	Questa classe permette l'accesso alle configurazioni dell'applicazione
	specificate nei fili .ini situati in: path/app/Config.
	Il path base da cui prelevare i file di configurazione può essere specificato
	utilizzando la function path($new_path).
	Come funziona?
	Si tratta di una classe statica avente un solo metodo, get($option_name).
	$option_name deve essere formattato nel seguente metodo:
		nome_file_ini.nome_opzione
	I file ini, alla prima richiesta di caricamento, vengono salvati in memoria,
	così da evitare nuove letture da file ai successivi accessi al medesimo
	set di configurazioni.

	Consideriamo un esempio:
	il file path/app/Config/app.ini contiene diverse opzioni
	----------- app.ini -----------
	one = 1
	key = 'stringa'
	-------------------------------

	Per accedere alle opzioni del file app.ini basta richiamare la funzione get
	nel modo seguente:
	$one = Pure\Config::get('app.one');
	$key = Pure\Config::get('app.key');
*/

namespace Pure;

class Config
{
	private function __construct(){}
	private function __destruct(){}

	// base path from which find config files
	private static $base_path = null;

	// configs caching
	private static $configs = array();

	// this let changing the base path
	public static function path($path = null){
		if(empty($path))
			return self::$base_path;
		self::$base_path = $path;
	}

	// retrieve a config option
	// returns null for errors
	public static function get($option_name, $path = null)
	{
		$parts = explode('.', $option_name);
		// Does the option_name contain the dot?
		if(count($parts) > 1)
		{
			if(empty($path))
				$path = self::$base_path;

			// split the string by the dot
			$config_name = $parts[0];
			if (strpos($config_name, '/') !== false) {
		    	// it is an absolute path
			}
			// it is a relative path, add the base path to the config name
			else $config_name = $path . '/' . $config_name;

			// if the file is not cached, load the file
			if(!array_key_exists($config_name, self::$configs))
			{
				// check if the file exists
				$filename = $config_name . '.ini';
				if(file_exists($filename))
					self::$configs[$config_name] = parse_ini_file($filename);
				else return null;
			}

			// return the option
			if(array_key_exists($parts[1], self::$configs[$config_name]))
				return self::$configs[$config_name][$parts[1]];
		}
		return null;
	}
}

?>
