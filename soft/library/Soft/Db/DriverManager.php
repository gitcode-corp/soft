<?php

namespace Soft\Db;

class DriverManager
{
    /**
     *
     * @var Driver\Driver
     */
    private $driver;
    
     /**
     * List of supported drivers and their mappings to the driver classes.
     *
     * @var array
     */
    private $driverMap = array(
           'pdo_mysql'  => '\Soft\Db\Driver\PDOMysqlDriver',

    );
     
    public function setDriver(Driver\Driver $driver)
    {
        $this->driver = $driver;
    }
    
    /**
     * 
     * @param Driver\Driver|array $driver
     * @throws Exception\DriverManagerInvalidArgumentException
     */
    public function getConnection($driver)
    {
        if ($driver instanceof Driver\Driver) {
            return $this->createConnection($driver)->connect();
        } elseif (is_array($driver) && $driver) {
            return  $this->createConnection($this->createDriver($driver))->connect();
        } else {
            $message = "Expected Soft\Db\Driver\Driver or params array.";
            throw new Exception\DriverManagerInvalidArgumentException($message);
        }
    }
    
    /**
     * 
     * @param array $params
     * @return \Soft\Db\driverClass
     * @throws Exception\DriverManagerInvalidArgumentException
     */
    private function createDriver(array $params)
    {
        if (isset($params['driver']) && !array_key_exists($params["driver"], $this->driverMap)) {
            $message = "Driver '" . $params['driver'] ."' is not supported";
            throw new Exception\DriverManagerInvalidArgumentException($message);
        } elseif (!isset($params['driver'])) {
            $message = "Expected driver param. Null given";
            throw new Exception\DriverManagerInvalidArgumentException($message);
        }
        
        $driverClass = $params['driver'];
        $userername = $this->getParam($params, 'username');
        $password = $this->getParam($params, 'password');
        $driverOptions = $this->getParam($params, 'driverOptions', []);
        
        return new $driverClass($params, $userername, $password, $driverOptions);
    }
    
    /**
     * 
     * @param array $params
     * @param mix $name
     * @param mix $default
     * @return mix
     */
    private function getParam(array $params, $name, $default = null)
    {
        if (isset($params[$name])) {
            return $params[$name];
        }
        
        return $default;
    }
    
    private function createConnection(Driver\Driver $driver)
    {
        return new Connection($driver);
    }
    
}
