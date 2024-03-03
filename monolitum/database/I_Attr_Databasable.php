<?php

namespace monolitum\database;

/**
 * Interface to mark an attribute as storable into a database
 */
interface I_Attr_Databasable
{
    /**
     * Retrieve the type to append to the name of the attribute in the DDL when creating the table into database.
     * @return string
     */
    function getDDLType();
}