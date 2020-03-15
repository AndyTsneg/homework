<?php

namespace Login\Helper;

use Login\Model\Message;
use Illuminate\Support\Str;
use Cache;

class MessageHandle extends AMessageHandle
{
    /**
     * Put message to storage
     *
     * @param $value
     * @return string
     */
    public static function put($value):string
    {
        $key = Str::random(32);
        $_SESSION[$key] = $value;
        return $key;
    }

    /**
     * Get message from storage
     *
     * @param $key
     * @return string
     */
    public static function get($key):string
    {
        $value = '';
        if(isset( $_SESSION[$key])){
            $value = $_SESSION[$key];
            self::del($key);
        }
        return $value;
    }

    /**
     * Delete message from storage
     *
     * @param $key
     * @return bool
     */
    public static function del($key):bool
    {
        unset($_SESSION[$key]);
       return true;

    }
}
