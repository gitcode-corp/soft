<?php

namespace Soft\Db\Driver;

class PDOMysqlDriver implements Driver
{
    /**
     *
     * @var \Soft\Db\Driver\Connection\PDOConnection
     */
    private $connection;
    
    /**
     *
     * @var array
     */
    private $params;
    
    /**
     *
     * @var string
     */
    private $username;
    
    /**
     *
     * @var string
     */
    private $password;
    
    /**
     *
     * @var array
     */
    private $driverOptions;
    
    /**
     * @param array       $params        All connection parameters passed by the user.
     * @param string|null $username      The username to use when connecting.
     * @param string|null $password      The password to use when connecting.
     * @param array       $driverOptions The driver options to use when connecting.
     */
    public function __construct(array $params, $username = null, $password = null, array $driverOptions = array())
    {
        $this->params = $params;
        $this->username = $username;
        $this->password = $password;
        $this->driverOptions = $driverOptions;
    }
    
    /**
     * {@inheritdoc}
     */
    public function connect()
    {
        if ($this->connection) {
            return $this->connection;
        }
        
        $this->connection = new \Soft\Db\Driver\Connection\PDOConnection(
            $this->constructPdoDsn(),
            $this->username,
            $this->password,
            $this->driverOptions
        );

        return $this->connection;
    }

    /**
     * Constructs the MySql PDO DSN.
     *
     * @param array $params
     *
     * @return string The DSN.
     */
    private function constructPdoDsn(array $params)
    {
        $dsn = 'mysql:';
        if (isset($this->params['host']) && $this->params['host'] != '') {
            $dsn .= 'host=' . $this->params['host'] . ';';
        }
        if (isset($this->params['port'])) {
            $dsn .= 'port=' . $this->params['port'] . ';';
        }
        if (isset($this->params['dbname'])) {
            $dsn .= 'dbname=' . $this->params['dbname'] . ';';
        }
        if (isset($this->params['unix_socket'])) {
            $dsn .= 'unix_socket=' . $this->params['unix_socket'] . ';';
        }
        if (isset($this->params['charset'])) {
            $dsn .= 'charset=' . $this->params['charset'] . ';';
        }

        return $dsn;
    }
    
    public function getName()
    {
        return "pdo_mysql";
    }
}
