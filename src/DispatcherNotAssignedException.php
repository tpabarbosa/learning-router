<?php

namespace tpab\Router;

class DispatcherNotAssignedException extends \Exception 
{
    public function __construct($code = 0, \Exception $previous = null) {
        $message = 'A Dispatcher is not assigned to Router.';
        parent::__construct($message, $code, $previous);
    }

}
