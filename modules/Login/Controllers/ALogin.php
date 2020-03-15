<?php

namespace Login\Controllers;

use Illuminate\Http\Request;

abstract class ALogin extends Base
{
    /**
     * Each login method has different way to do authorization
     *
     * @param Request $request
     * @return mixed
     */
    abstract public function goAuth(Request $request);
}
