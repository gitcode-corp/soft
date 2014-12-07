<?php

namespace Soft\Db\Driver;

interface Driver
{
  /**
     * Attempts to create a connection with the database.
     *
     * @return \Soft\Db\Driver\Connection The database connection.
     */
    public function connect();


    /**
     * Gets the name of the driver.
     *
     * @return string The name of the driver.
     */
    public function getName();
}
