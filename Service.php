<?php

/// Copyright (c) Vito Domenico Tagliente
/// Generic Service interface

namespace Pure;

abstract class Service
{
	/// Executed after the application initialization
	abstract function boot();

	/// Executed before the routing dispatch
	abstract function start();

	/// Executed when the application is going to be closed
	abstract function stop();
}
