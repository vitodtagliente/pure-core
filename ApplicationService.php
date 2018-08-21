<?php

/*
	Interfaccia base per la definizione dei servizi dell'applicazione.
	- La fase di boot è eseguita all'avvio dell'applicazione, quando tutte le
	  API sono inizializzate ed la connessione al database viene stabilita.
	- La fase di start è eseguita prima di eseguire dispatch delle rotte.
	- La fase di stop viene eseguita all'arresto dell'applicazione.
*/

namespace Pure;

abstract class ApplicationService
{
	abstract function boot();

	abstract function start();

	abstract function stop();
}

?>
