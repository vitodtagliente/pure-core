<?php

/*
    Intefaccia per la creazione di schemi
*/

namespace Pure;
use Pure\ORM\Schema;
use Pure\ORM\SchemaBuilder;

abstract class SchemaHandler {

    // if $override_schema is equal to true
    // if the table exists it will be deleted
    public function create($override_schema = false)
    {
        if(Schema::exists($this->table()))
        {
            if($override_schema)
                Schema::drop($this->table());
            else return true;
        }

        $schema = new SchemaBuilder($this->table());
        $this->define($schema);
        return Schema::create( $schema->query() );
    }

    // return the table name
    public abstract function table();

    // define the schema fields
    protected abstract function define($schema);

}

?>
