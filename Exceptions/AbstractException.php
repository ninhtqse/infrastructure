<?php
namespace Infrastructure\Exceptions;

class AbstractException extends \Exception implements ExceptionInterface
{
    protected $status = 'error';
    protected $code;
    protected $data;
    protected $parameters;

    public function __construct($code = 400, $message = null, $data = null, $parameters = [])
    {
        parent::__construct($message, 400);
        $this->data = $data;
        $this->code = $code;
        $this->parameters = $parameters;
    }

    public function getData()
    {
        return $this->data;
    }
    public function getStatus()
    { 
        return $this->code;
    }
    public function getParameters() {
        return $this->parameters;
    }
}
