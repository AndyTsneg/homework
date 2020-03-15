<?php

namespace Login\Helper;

abstract class AMessageHandle
{
    abstract public static function put($value):string;
    abstract public static function get($key):string;
    abstract public static function del($key):bool;
}
