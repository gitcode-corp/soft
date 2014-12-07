<?php

namespace Soft\Http;

use Soft\Http\Response\Response as ResponseInterface;

class Response implements ResponseInterface
{
    const PROTOCOL = 'HTTP/1.1';

    /**
     * Http code.
     * @var string
     */
    private $code = StatusCode::OK;
    
    /**
     * Mime type.
     * @var string
     */
    private $mimeType = MimeType::HTML;
    
    /**
     * Response headers.
     * @var  array
     */
    private $headers = [];

    /**
     * Template.
     * @var string  
     * @return $this
     */
    private $body = '';
    
    /**
     * @param string $name
     * @param string $value
     * @param bool $replace
     * @return \Soft\Http\Response
     */
    public function addHeader($name, $value, $replace = false)
    {
        $this->headers[] = new Response\Header($name, $value, $replace);

        return $this;
    }
    
    public function flushHeaders()
    {
        foreach ($this->getHeaders() as $header) {
            $header->flush();
        }
        
        if(!$this->headers) {
            $header = new Response\Header('Content-Type', $this->getMimeType());
            $header->flush();
        }
        
        $header = new Response\Header(self::PROTOCOL, $this->getCode());
        $header->flush();
    }

    /**
     * @param string $content
     * @return \Soft\Http\Response
     */
    public function setBody($content)
    {
        $this->body = $content;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
    
    public function outputBody()
    {
        echo $this->body;
    }
    
    public function getMimeType()
    {
        return $this->mimeType;
    }
    
    public function flush()
    {
        $this->flushHeaders();

        $this->outputBody();
    }
    
    /**
     * Get Response headers.
     * @return Header[]
     */
    public function getHeaders()
    {
        return $this->headers;
    }
    
    /**
     * Set response headers.
     * @param  array $headers
     */
    public function setHeaders(array $headers)
    {
        $data = [];
        foreach ($headers as $header) {
            if ($header instanceof Response\Header) {
                $data[] = $header;
            } elseif (is_array($header) && isset($header['name']) && isset($header['value'])) { 
                $replace = (isset($header['replace'])) ? true : false;
                $data[] = new Response\Header($header['name'], $header['value'], $replace);
            }  
        }
        
        $this->headers = $data;
    }
    
    /**
     * 
     * @param type $code
     * @return \Soft\Http\Response
     */
    public function setCode($code)
    {
        $this->code = (int) $code;
        
        return $this;
    }
    
    /**
     * 
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }
}
