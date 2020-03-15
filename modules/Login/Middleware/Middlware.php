<?php

namespace Login\Middleware;


use Closure;

class Middlware
{
    public function handle($request, Closure $next)
    {

        $response = $next($request);

        return $response;
    }
}