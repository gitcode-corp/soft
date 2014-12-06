<?php

namespace Soft\Db\Driver;

interface Driver
{
  /**
     * Attempts to create a connection with the database.
     *
     * @param array       $params        All connection parameters passed by the user.
     * @param string|null $username      The username to use when connecting.
     * @param string|null $password      The password to use when connecting.
     * @param array       $driverOptions The driver options to use when connecting.
     *
     * @return \Soft\Db\Driver\Connection The database connection.
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = array());


    /**
     * Gets the name of the driver.
     *
     * @return string The name of the driver.
     */
    public function getName();
}
