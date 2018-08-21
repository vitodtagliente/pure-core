<?php

/*
    Interfaccia base per la definizione di comandi console per l'applicazione.
    Ogni comando console è caratterizzato da:
    - una funzione di esecuzione 'execute', che presenta comportamenti
    differenti in base al numero e al tipo di parametri utilizzati.
    - una funzione di aiuto 'help' per la stampa su terminale della
    guida d'utilizzo
*/

namespace Pure;

abstract class Command {

    public abstract function execute($arguments);

    public abstract function help();

}

?>
