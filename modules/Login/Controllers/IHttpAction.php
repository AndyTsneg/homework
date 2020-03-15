<?php

namespace Login\Controllers;

use Illuminate\Http\Request;

interface IHttpAction
{
    /**
     * All controller need to have execute to be enter point.
     *
     * @param Request $request
     * @return mixed
     */
    public function execute(Request $request);
}
