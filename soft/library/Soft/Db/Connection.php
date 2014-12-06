<?php

namespace Soft\Db;

use Driver\Connection\Connection as DriverConnection;

class Connection implements DriverConnection
{
    /**
     *
     * @var Driver\Driver
     */
    private $driver;
    
    /**
     *
     * @var Driver\Connection\Connection
     */
    private $connection;
    
    /**
     * 
     * @param \Soft\Db\Driver\Driver $driver
     */
    public function __construct(Driver\Driver $driver)
    {
        $this->driver = $driver;
    }
    
    /**
     * 
     * @return \Soft\Db\Connection
     */
    public function connect()
    {
        $this->connection = $this->driver->connect();
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function prepare($prepareString)
    {
        return $this->connection->prepare($prepareString);
    }

    /**
     * {@inheritdoc}
     */
    public function query()
    {
        return $this->connection->query();
    }

    /**
     * {@inheritdoc}
     */
    public function quote($input, $type=\PDO::PARAM_STR)
    {
        return $this->connection->quote($input, $type);
    }
}

