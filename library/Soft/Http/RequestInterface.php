<?php

namespace Soft\Http;

interface RequestInterface
{
    /**
     * @return string
     */
    public function getMethod();
    
    /**
     * @return strinh
     */
    public function getRequestUri();
}

