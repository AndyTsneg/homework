<?php

namespace Login\Exceptions;

use Exception;
use Login\Helper\MessageHandle;

class myException extends Exception
{
    var $code ='';
    var $message = '';
    private static $instance = null;

    /**
     * @param $code
     * @param $message
     */
    public function setParam($code,$message)
    {
        $this->code    = $code;
        $this->message = $message;
    }

    /**
     * Keep only one object in the memory
     *
     * @param $code
     * @param $message
     * @return myException|null
     */
    public static function setException($code,$message)
    {
        if (self::$instance == null) {
            self::$instance = new myException();
        }
        self::$instance->setParam($code, $message);
        return self::$instance;
    }
}
