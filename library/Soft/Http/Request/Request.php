<?php

namespace Soft\Http\Request;

interface Request
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

