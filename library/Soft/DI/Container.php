<?php

namespace Soft\DI;

use Soft\DI\Container\Container as ContainerInterface;

class Container implements ContainerInterface
{
    /**
     *
     * @var array
     */
    private $services;
    
    /**
     * 
     * @param string $name
     * @param callable $callable
     * @param bool $prototype
     * @return \Soft\DI\Container
     * @throws \InvalidArgumentException
     */
    public function add($name, $callable, $prototype = false)
    {
        if (!is_object($callable) || !method_exists($callable, '__invoke')) {
            throw new \InvalidArgumentException('Callable is not a Closure or invokable object.');
        }

        $this->services[$name] = ['service' => $callable, 'prototype' => $prototype];

        return $this;
    }
    
    /**
     * @param string $name
     * @return mix | null
     */
    public function get($name)
    {
        if ($this->has($name)) {
            return $this->services[$name];
        }
        
        return null;
    }
    
    /**
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->services);
    }
}
