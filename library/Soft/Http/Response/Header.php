<?php

namespace Soft\Http\Response;

class Header
{
    /**
     * Header name.
     * @var string
     */
    private $name = '';
    
    /**
     * Header value.
     * @var string
     */
    private $value = '';
    
    /**
     * Wheteher replace existing header.
     * @var bool
     */
    private $replace = false;
    
    /**
     * Create response Header.
     * @param string $name
     * @param string $value
     * @param bool $replace
     */
    public function __construct($name, $value,$replace = false) 
    {
        $this->setName($name);
        $this->setValue($value);
        $this->setReplace($replace);
    }
    
    public function flush()
    {
        header($this->getName() . ":" . $this->getValue(), $this->getReplace());
    }
    
    /**
     * Whether headers have been sent.
     * @return bool
     */
    public function headersSent()
    {
        return headers_sent();
    }
    
    /**
     * Get header name.
     * @return string
     */
    private function getName()
    {
        return $this->name;
    }
    
    /**
     * Set header name.
     * @param string $value
     */
    private function setName($value)
    {
        $this->name = (string)$value;
    }
    
    /**
     * Get header value.
     * @return string
     */
    private function getValue()
    {
        return $this->name;
    }
    
    /**
     * Set header name.
     * @param string $value
     */
    private function setValue($value)
    {
        $this->value = (string)$value;
    }
    
    /**
     * Whether replace header.
     * @return bool
     */
    private function getReplace()
    {
        return $this->name;
    }
    
    /**
     * Whether replace header.
     * @param bool $value
     */
    private function setReplace($value)
    {
        $this->replace = (bool)$value;
    }
}
