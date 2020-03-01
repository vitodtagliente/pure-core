<?php


/// Copyright (c) Vito Domenico Tagliente
/// Common dev APIs

/// Retrieve the root path of the application
/// or the absoulte path to something passed that exists inside of the project
/// @param path - The path to check
/// @return - The path
function base_path($path = null){
	if(isset($path))
	{
		return getcwd() . '/' . trim($path, '/');
	}
	return getcwd();
}

/// Retrieve a config option
/// @param option - The name of the option
/// @param path - The path form which look for the config files
/// @return - The option if exists
function config($option, $path = null){
	return Pure\Config::get($option, $path);
}

// return
// 1. the base application url
// 2. a relative ulr to something inside the project

function base_url($action = null, $params = array()){
	$url_params = null;
	if(!empty($params)){
		$comma = '?';
		foreach($params as $key => $value){
			$url_params .= "$comma$key=$value";
			$comma = '&';
		}
	}
	$base = str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]);
	return $base . trim($action, '/') . $url_params;
}

/// Redirect
/// @param url - The URL od destination
/// @param data - The parameters to pass
/// @param code - The status code
function redirect($url, $data = array(), $code = 302){
	if(!empty($data))
	{
		$url .= '?' . http_build_query($data);
	}

	@header( "Location: {$url}", true, $code );
	exit;
}

/// Retrieve a request parameter
/// @param name - The name of the request variable
/// @return - The value
function request($name)
{
	return Pure\Request::input($name);
}

/// Retrieve a get variable
/// @param name - The name of the get variable
/// @return - The value of the variable
function get($name)
{
	return Pure\Request::get($name);
}

/// Retrieve a post variable
/// @param name - The name of the variable
/// @return - The value of the variable
function post($name)
{
	return Pure\Request::post($name);
}

/// Include all the files under a given directory that have a specific extension
/// @param directory - The directory to llok at
/// @param extension - The extension to take care, php files by default
function include_directory($directory, $extension = '.php') {
	if(is_dir($directory))
	{
		$scan = scandir($directory);
		unset($scan[0], $scan[1]); //unset . and ..
		foreach($scan as $file)
		{
			$current_path = $directory . '/' . $file;
			if(is_dir($current_path))
			{
				include_directory($current_path, $extension);
			}
			else
			{
				if(strpos($file, $extension) !== false)
				{
					include_once($current_path);
				}
			}
		}
	}
}

/// Retrieve the application
/// @return - The application
function app(){
	return Pure\Application::main();
}

/// Retrieve the router
/// @return - The router
function router(){
	return Pure\Routing\Router::main();
}

/// Make a view
/// @param template_file - The template to be used
/// @param params - The list of params of the view
/// @return - The view
function view($template_file, $params = array()){
	return Pure\Template\View::make($template_file, $params);
}

/// Debug a variable
/// @param variable - The variable to examine
function dd($variable){
	var_dump($variable);
}

/// Print a string in output
/// @param string - the string to print out
function out($string){
	echo $string;
}

/// Print a line of characters
/// @param string - The string to print out
function outline($string = null){
	echo $string . "\n";
}

/// Generate an http response in json format
/// @param data - The data to format and print
function response($data){
	echo json_encode($data);
}

/// Retrieve the user that is logged in
/// @return - The user model
function user(){
	return Pure\Auth::user();
}

/// Check is a variable is an associative array
/// @param variable - The variable to check
/// @return - True if the variable is an associative array
function is_associative_array($variable)
{
    return array_keys($variable) !== range(0, count($variable) - 1);
}

/// Instantiate an object of a given class if the class exists
/// @param classname - The name of the class
/// @return - The object if the class exists
function instantiate($classname)
{
    if (class_exists($classname)) {
        return new $classname;
    }
    return null;
}

/// Instantiate an object of a given class check the class hierarchy
/// @param classname - The name of the class
/// @param parent_classname - The name of the parent class
/// @return - The object if the class exists
function instantiate_if($classname, $parent_classname)
{
    $object = instantiate($classname);
    if($object && is_a($object, $parent_classname)) {
        return $object;
    }
    return null;
}