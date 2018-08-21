<?php

/*
    Questa classe modella l'applicazione. Implementa il pattern singleton,
    pertanto si avrà una sola instanza attiva, ottenibile con la funzione
    statica main(). $app = Pure\Application::main();
    E' possibile avviare l'applicazione tramite il metodo 'run';

    All'avvio, vengono svolte diverse operazioni:
    - Vengono caricati i servizi:
      Per essere caricati, occorre registrare le classi di tali all'interno del
      file di configurazione 'app.ini'. I servizi permettono di customizzare
      il comportamento dell'applicazione. All'interno dei servizi, è possibile
      definire path alternativi da cui caricare le routes, le view e soprattutto
      è possibile registrare le classi schema dall'esterno della configurazione
      di default definita nel file 'app.ini'.
    - Vengono caricati gli schema caricando le classi dal file app.ini.
      In alternativa, è possibile registrare le classi anche a livello di codice
      attraverso il metodo registerSchema('Namespace\Schemas\SchemaName');
    - Carica le rotte dalla direcotry di default definita nel file app.ini e da
      quelle registrate tramite il metodo loadRoutesFrom(path);
    - Carica le viste suddivise per namespace.

    E' possibile customizzare il comportamento dell'applicazione a seguito di una
    navigazione verso un url invalido.
    $app->routing_error_handler = function(){ ... };
*/

namespace Pure;
use Pure\Routing\Router;

class Application {

    private static $instance = null;

    private function __construct(){}
    public function __destruct(){}

    // singleton pattern
    public static function main(){
        if(!isset(self::$instance))
            self::$instance = new Application;
        return self::$instance;
    }

    // application services
    private $services = array();

    // paths where to find routes
    private $route_paths = array();

    // application schemas: array of classes
    private $schemas = array();

    // the application running state
    private $running = false;

    // error handler function
    public $routing_error_handler = null;

    public function run($shell_mode = false, $argv = array()){
        // run the application only one time
        if($this->running) return;
        $this->running = true;

        // boot the application and services
        $this->boot();

        // create all the tables
        $this->loadSchemas();

        // load routes
        $this->loadRoutesFrom('app/Routes');
        foreach($this->route_paths as $path){
            include_directory($path, '.php');
        }

        // load views
        $this->loadViewsFrom('app/Views');

        // the application is ready, start all the services
        $this->start();

        if($shell_mode)
        {
            $command = array_shift($argv);
            $this->execute_command($command, $argv);
        }
        else
        {
            // dispatch routing
            $router = Router::main();
            if(isset($router))
            {
                if(!$router->dispatch())
                {
                    // Error, route not found
                    if(is_callable($this->routing_error_handler))
                        call_user_func($this->routing_error_handler);
                    else echo "Error, route not found!";
                }
            }
        }

        // stop all the services
        $this->stop();
    }

    private function execute_command($command, $arguments = array()){
        if(!isset($command))
        {
            return;
        }

        $class_name = $command;
        // check for the command class,
        // check if it is a pure command
        if (strpos($class_name, '\\') !== false) {
            // it is an absolute class name with namespace
        }
        // else search from Pure and App namespace
        else
        {
            if(class_exists('\Pure\\Commands\\' . $class_name))
                $class_name = '\Pure\\Commands\\' . $class_name;
            else if(class_exists('\App\\Commands\\' . $class_name))
                $class_name = '\App\\Commands\\' . $class_name;
        }

        // check if classname exists
        if(!class_exists($class_name))
        {
            echo "$class_name class not found!";
            return;
        }

        $cmd_object = new $class_name;

        // check if the class extends Pure\Command
        if(!$cmd_object || !is_a($cmd_object, '\Pure\Command'))
        {
            echo "$class_name does not extend Pure\Command, invalid command class!";
            return;
        }

        // if args contain --help or -h,
        // launch the help method
        // else execute the command
        if(in_array('--help', $arguments) || in_array('-h', $arguments))
            $cmd_object->help();
        else $cmd_object->execute($arguments);
    }

    // boot the application and services
    private function boot()
    {
        // load service classes by the config, instantiate here
        $services_classes = config('app.services');
        if(!empty($services_classes))
        {
            foreach($services_classes as $service_class)
            {
                if(class_exists($service_class)){
                    $service = new $service_class;
                    if($service && is_a($service, '\Pure\ApplicationService'))
                    {
                        array_push($this->services, $service);
                    }
                }
            }
        }

        // boot services
        foreach($this->services as $service)
        {
            $service->boot();
        }
    }

    // start all the application services
    private function start()
    {
        // start services
        foreach($this->services as $service)
        {
            $service->start();
        }
    }

    // end all the application services
    private function stop()
    {
        // stop services
        foreach($this->services as $service)
        {
            $service->stop();
        }
    }

    private function loadSchemas(){
        // load schemas from app.ini and from registered classes
        $schema_classes = array_merge($this->schemas, Config::get('app.schemas'));

        // $schema_classes should be an array
        if(empty($schema_classes))
            return;

        foreach($schema_classes as $schema_class){
            if(!empty($schema_class) && class_exists($schema_class))
    		{
                $schema = new $schema_class;
                if($schema && is_a($schema, '\Pure\SchemaHandler'))
                {
                    $success = call_user_func(array($schema, 'create'));
                    if(!$success)
                    {
                        dd("Schema error");
                        // TODO: error management
                    }
                }
    		}
        }
    }

    public function loadRoutesFrom($path){
        if(in_array($path, $this->route_paths) == false)
            array_push($this->route_paths, $path);
    }

    public function loadViewsFrom($path, $namespace = null){
        Template\View::namespace($path, $namespace);
    }

    public function registerSchema($schema_class){
        array_push($this->schemas, $schema_class);
    }
}

?>
