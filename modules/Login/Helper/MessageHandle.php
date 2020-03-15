<?php

namespace Login\Helper;

use Login\Model\Message;
use Login\Helper\AMessageHandle;
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
        Cache::store('file')->put($key,$value, 60);
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
        if (Cache::has($key)) {
            $value = Cache::store('file')->get($key);
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
        Cache::forget($key);
       return true;

    }
}
