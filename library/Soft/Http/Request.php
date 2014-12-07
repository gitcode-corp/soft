<?php

namespace Soft\Http;

class Request implements RequestInterface
{
    const METHOD_GET     = 'GET';
    const METHOD_POST    = 'POST';
    
    /**
     * @var string
     */
    private $method = self::METHOD_GET;
    
    /**
     * @var array
     */
    private $params = [];
    
    /**
     * @var string
     */
    private $requestUri;
    
    /**
     * @var bool
     */
    private $isXmlHttpRequest;
    
    private $content;
    
    public function __construct()
    {
        $this->requestUri = $_SERVER['REQUEST_URI'];
        
        $this->params['all'] = $_REQUEST;
        $this->params['get'] = $_GET;
        $this->params['post'] = $_POST;
        
        $this->setMethod($_SERVER['REQUEST_METHOD']);
        
        if(
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        ) {
            $this->isXmlHttpRequest = true;
        }
    }
    
    /**
     * 
     * @param string $method
     */
    private function setMethod($method)
    {
        $method = strtoupper($method);
        if (self::METHOD_POST === $method) {
            $this->method = self::METHOD_POST;
        }
    }
    
    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
    
    /**
     * 
     * @return string
     */
    public function getRequestUri()
    {
        return $this->requestUri;
    }
    
    /**
     * 
     * @param string $name
     * @param mix $default
     * @return mix
     */
    public function getParam($name, $default = null)
    {
        if(isset($this->params['get'][$name])) {
            return $this->params['get'][$name];
        }
        
        return $default;
    }
    
    /**
     * 
     * @return array
     */
    public function getParams()
    {
        return $this->params['get'];
    }
    
    /**
     * 
     * @param string $name
     * @param mix $default
     * @return mix
     */
    public function postParam($name, $default = null)
    {
        if(isset($this->params['post'][$name])) {
            return $this->params['post'][$name];
        }
        
        return $default;
    }
    
    /**
     * 
     * @return array
     */
    public function postParams()
    {
        return $this->params['post'];
    }
    
    /**
     * @return type
     */
    public function getContent()
    {
        if (empty($this->content)) {
            $requestBody = file_get_contents('php://input');
            if (strlen($requestBody) > 0) {
                $this->content = $requestBody;
            }
        }

        return $this->content;
    }
    
    public function isGet()
    {
        return self::METHOD_GET === $this->method;
    }
    
    public function isPost()
    {
        return self::METHOD_POST === $this->method;
    }
}
