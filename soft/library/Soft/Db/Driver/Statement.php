<?php

namespace Soft\Db\Driver;


interface Statement
{
    /**
     * @param mixed   $param
     * @param mixed   $value
     * @param integer $type
     *
     * @return boolean
     */
    function bindValue($param, $value, $type = null);

    /**
     * Binds a PHP variable
     * 
     * @param mixed        $column  
     * @param mixed        $variable
     * @param integer|null $type     
     * @param integer|null $length 
     *
     * @return boolean TRUE on success or FALSE on failure.
     */
    function bindParam($column, &$variable, $type = null, $length = null);

    /**
     * Fetches the SQLSTATE associated with the last operation on the statement handle.
     *
     * @see Doctrine_Adapter_Interface::errorCode()
     *
     * @return string The error code string.
     */
    function errorCode();

    /**
     * Fetches extended error information associated with the last operation on the statement handle.
     *
     * @see Doctrine_Adapter_Interface::errorInfo()
     *
     * @return array The error info array.
     */
    function errorInfo();

    /**
     * Executes a prepared statement
     *
     * @param array|null $params 
     *
     * @return boolean
     */
    function execute($params = null);

    /**
     * Returns the number of rows affected by the last DELETE, INSERT, or UPDATE statement
     * executed by the corresponding object.
     *
     * @return integer The number of rows.
     */
    function rowCount();
    
    /**
     * Sets the fetch mode to use while iterating this statement.
     *
     * @param integer $fetchMode
     * @param mixed   $arg2
     * @param mixed   $arg3
     *
     * @return boolean
     */
    public function setFetchMode($fetchMode, $arg2 = null, $arg3 = null);

    /**
     * @param integer|null
     *
     * @return mixed
     */
    public function fetch($fetchMode = null);

    /**
     * Returns an array containing all of the result set rows.
     *
     * @param integer|null $fetchMode 
     *
     * @return array
     */
    public function fetchAll($fetchMode = null);
}
