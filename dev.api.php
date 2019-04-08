<?php

/*
 * Common application functions
 */

// return
// 1. the root path of the application
// 2. a relative path to something inside the project

function base_path($path = null){
	if(isset($path))
		return getcwd() . '/' . trim($path, '/');
	return getcwd();
}

// return a config variable
// example: $app_title = config('app.title');
// where app is the file root/app/config/app.php
// and title is a variable inside this ini file

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

// redirect function

function redirect($url, $data = array(), $code = 302){
	if(!empty($data))
		$url .= '?' . http_build_query($data);

	@header( "Location: {$url}", true, $code );
	exit;
}

// return the request input

function request($key){
	return Pure\Request::input($key);
}

function get($key){
	return Pure\Request::get($key);
}

function post($key){
	return Pure\Request::post($key);
}

// this function include all files with a specified extension
// contained by a folder and all the subdirectories

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

// return the application
function app(){
	return Pure\Application::main();
}

// return the router
function router(){
	return Pure\Routing\Router::main();
}

// make a view
function view($template_file, $params = array()){
	return Pure\Template\View::make($template_file, $params);
}

// var_dump alias
function dd($variable){
	var_dump($variable);
}

// commands printing functions
function out($string){
	echo $string;
}

function outline($string = null){
	echo $string . "\n";
}

// http response
function response($data){
	echo json_encode($data);
}

// return the logged in user model
function user(){
	return Pure\Auth::user();
}

// object to associative array conversion
function data($obj){
	if(empty($obj)) return array();
	if(method_exists($obj, 'getData')) return $obj->getData();
	if(is_array($obj)){	
		if(is_object($obj[0]))
		{
			$records = array();
			foreach ($obj as $o) {
				array_push($records, data($o));
			}
			return $records;			
		}
		return $obj;
	}
	return null;
}

?>
