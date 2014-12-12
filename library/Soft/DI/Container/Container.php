<?php

namespace Soft\DI\Container;

interface Container
{
    /**
     * @param string $name
     * @param Closure $callable
     * @param bool $prototype
     * 
     * @return $this
     */
    public function add($name, $callable, $prototype = false);
    
    /**
     * @param string $name
     * @return mix | null
     */
    public function get($name);
    
    /**
     * @param string $name
     * @return bool
     */
    public function has($name);
}
