<?php

namespace Soft\Http\Response;

interface Response
{
    /**
     * @param string $code
     */
    public function setCode($code);
    
    /**
     * string
     */
    public function getCode();
    
    /**
     * 
     * @param array $headers
     */
    public function setHeaders(array $headers);
    
    /**
     * @return array
     */
    public function getHeaders();
    
    /**
     * @param string $body
     */
    public function setBody($body);
    
    /**
     * @return string
     */
    public function getBody();
}

