<?php

namespace Soft\Db\Driver\Connection;

interface Connection
{
    /**
     * Prepares a statement for execution and returns a Statement object.
     *
     * @param string $prepareString
     *
     * @return \Soft\Db\Driver\Statement
     */
    function prepare($prepareString);

    /**
     * Executes an SQL statement, returning a result set as a Statement object.
     *
     * @return \Soft\Db\Driver\Statement
     */
    function query();

    /**
     * Quotes a string for use in a query.
     *
     * @param string  $input
     * @param integer $type
     *
     * @return string
     */
    function quote($input, $type=\PDO::PARAM_STR);
}