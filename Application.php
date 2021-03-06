<?php

/// Copyright (c) Vito Domenico Tagliente
/// The pure application

namespace Pure;
use Pure\Routing\Router;

class Application
{
    /// singleton pattern
    private static $s_instance = null;

    /// the list of services of the application
    private $m_services = array();

    // paths where to find routes
    private $m_routePaths = array();

    // Array of classes of schemas
    private $m_schemas = array();

    // Express the state of the application
    private $m_isRunning = false;

    // error handler function
    public $m_errorHandler = null;

    /// construct
    private function __construct()
    {
    }

    /// destruct
    public function __destruct()
    {
    }

    /// Execute the application
    /// @param app_directory - The root directory of the application
    /// @param shell_mode - The mode of execution of the application
    /// @param argv - The list of arguments
    public static function execute($app_directory, $shell_mode = false, $argv = array())
    {
        // set the base config directory
        $app_directory = rtrim($app_directory, '/');
        Config::path($app_directory . '/app/Config');

        // start the session
        Session::start(Config::get('app.security_string'));

        // configure the auth interface
        Auth::model(Config::get('app.auth_class_name'));

        // prepare the database
        ORM\Database::prepare(new ORM\ConnectionSettings(
            Config::get('database.' . Config::get('database.active'))
        ));

        // run the application
        self::main()->run($shell_mode, $argv);

        // close the database connection
        ORM\Database::end();
    }

    /// singleton pattern
    /// @return - The main instance of the application
    public static function main()
    {
        if (!isset(self::$s_instance)) {
            self::$s_instance = new Application;
        }
        return self::$s_instance;
    }

    /// Run the application
    /// @param shell_mode - The mode of execution of the application
    /// @param argv - The list of arguments
    private function run($shell_mode = false, $argv = array())
    {
        // run the application only one time
        if ($this->m_isRunning) return;

        // update the application state
        $this->m_isRunning = true;

        // boot the application and services
        $this->boot();

        // create all the tables
        $this->loadSchemas();

        // load routes
        $this->loadRoutesFrom('app/Routes');
        foreach ($this->m_routePaths as $path) {
            include_directory($path, '.php');
        }

        // load views
        $this->loadViewsFrom('app/Views');

        // the application is ready, start all the services
        $this->start();

        if ($shell_mode) {
            $command = array_shift($argv);
            $this->execute_command($command, $argv);
        } else {
            // dispatch routing
            $router = Router::main();
            if (isset($router)) {
                if (!$router->dispatch()) {
                    // Error, route not found
                    if (is_callable($this->m_errorHandler))
                        call_user_func($this->m_errorHandler);
                    else echo "Error, route not found!";
                }
            }
        }

        // stop all the services
        $this->stop();
    }

    private function execute_command($command, $arguments = array())
    {
        if (!isset($command)) {
            return;
        }

        $class_name = $command;
        // check for the command class,
        // check if it is a pure command
        if (strpos($class_name, '\\') !== false) {
            // it is an absolute class name with namespace
        } // else search from Pure and App namespace
        else {
            if (class_exists('\Pure\\Commands\\' . $class_name))
                $class_name = '\Pure\\Commands\\' . $class_name;
            else if (class_exists('\App\\Commands\\' . $class_name))
                $class_name = '\App\\Commands\\' . $class_name;
        }

        // check if classname exists
        if (!class_exists($class_name)) {
            echo "$class_name class not found!";
            return;
        }

        $cmd_object = new $class_name;

        // check if the class extends Pure\Command
        if (!$cmd_object || !is_a($cmd_object, '\Pure\Command')) {
            echo "$class_name does not extend Pure\Command, invalid command class!";
            return;
        }

        // if args contain --help or -h,
        // launch the help method
        // else execute the command
        if (in_array('--help', $arguments) || in_array('-h', $arguments))
            $cmd_object->help();
        else $cmd_object->execute($arguments);
    }

    /// boot the application and services
    /// Instantiate all the services
    private function boot()
    {
        // load service classes by the config and instantiate them
        $services_classes = config('app.services');
        if (is_array($services_classes))
        {
            foreach ($services_classes as $service_class)
            {
                $service = instantiate_if($service_class, '\Pure\Service');
                if($service) {
                    array_push($this->m_services, $service);
                }
            }
        }

        // boot services
        foreach ($this->m_services as $service)
        {
            $service->boot();
        }
    }

    /// Start all the services
    private function start()
    {
        foreach ($this->m_services as $service)
        {
            $service->start();
        }
    }

    /// Stop all the services
    private function stop()
    {
        foreach ($this->m_services as $service)
        {
            $service->stop();
        }
    }

    private function loadSchemas()
    {
        // load schemas from app.php and from registered classes
        $schema_classes = array_merge($this->m_schemas, Config::get('app.schemas'));

        // $schema_classes should be an array
        if (empty($schema_classes) || !is_array($schema_classes))
            return;

        foreach ($schema_classes as $schema_class) {
            $table = $schema_class::table();
            if (!ORM\Schema::exists($table)) {
                if (!ORM\Schema::create($schema_class)) {
                    dd("Schema error");
                    // TODO: error management
                }
            }
        }
    }

    public function loadRoutesFrom($path)
    {
        if (in_array($path, $this->m_routePaths) == false)
            array_push($this->m_routePaths, $path);
    }

    public function loadViewsFrom($path, $namespace = '::')
    {
        Template\View::namespace($namespace, $path);
    }

    /// Register a new Schema
    public function registerSchema($schema_class)
    {
        if (class_exists($schema_class)) {
            array_push($this->m_schemas, $schema_class);
        }
    }
}
