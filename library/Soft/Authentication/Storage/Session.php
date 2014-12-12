<?php

namespace Soft\Authentication\Storage;

class Session implements Storage
{ 
    /**
     * Default session namespace
     */
    const NAMESPACE_DEFAULT = 'SOFT_AUTH';

    /**
     * Session namespace
     *
     * @var mixed
     */
    protected $namespace = self::NAMESPACE_DEFAULT;


    /**
     * @param  string $namespace
     */
    public function __construct($namespace = null)
    {
        if ($namespace !== null) {
            $this->namespace = $namespace;
        }
    }

    /**
     * Returns the session namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return !isset($_SESSION[$this->namespace]);
    }

    /**
     * Defined by Zend\Authentication\Storage\StorageInterface
     *
     * @return mixed
     */
    public function read()
    {
        return $_SESSION[$this->member];
    }

    /**
     * @param  mixed $contents
     * @return void
     */
    public function write($contents)
    {
        $_SESSION[$this->namespace] = $contents;
    }

    /**
     * @return void
     */
    public function clear()
    {
        unset($_SESSION[$this->namespace]);
    }
}


