<?php

namespace Soft\Db;

class DriverManager
{
     /**
     * List of supported drivers and their mappings to the driver classes.
     *
     * @var array
     */
    private static $driverMap = array(
           'pdo_mysql'  => '\Soft\Db\Driver\PDOMysqlDriver',

    );
     
    /**
     * 
     * @param Driver\Driver|array $driver
     * @throws Exception\DriverManagerInvalidArgumentException
     */
    public function getConnection($driver)
    {
        if ($driver instanceof Driver\Driver) {
            return self::createConnection($driver)->connect();
        } elseif (is_array($driver) && $driver) {
            return  self::createConnection(self::createDriver($driver))->connect();
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
    private static function createDriver(array $params)
    {
        if (isset($params['driver']) && !array_key_exists($params["driver"], self::$driverMap)) {
            $message = "Driver '" . $params['driver'] ."' is not supported";
            throw new Exception\DriverManagerInvalidArgumentException($message);
        } elseif (!isset($params['driver'])) {
            $message = "Expected driver param. Null given";
            throw new Exception\DriverManagerInvalidArgumentException($message);
        }
        
        $driverClass = self::$driverMap[$params['driver']];
        $userername = self::getParam($params, 'username');
        $password = self::getParam($params, 'password');
        $driverOptions = self::getParam($params, 'driverOptions', []);
        
        return new $driverClass($params, $userername, $password, $driverOptions);
    }
    
    /**
     * 
     * @param array $params
     * @param mix $name
     * @param mix $default
     * @return mix
     */
    private static function getParam(array $params, $name, $default = null)
    {
        if (isset($params[$name])) {
            return $params[$name];
        }
        
        return $default;
    }
    
    private static function createConnection(Driver\Driver $driver)
    {
        return new Connection($driver);
    }
    
}
