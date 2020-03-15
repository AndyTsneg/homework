<?php

namespace Login\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Login\Helper\MessageHandle;
use View;

class Login extends base implements IHttpAction
{
    /**
     * Enter point for sign in
     * @param Request $request
     * @return Factory|\Illuminate\View\View
     */
    public function execute(Request $request)
    {
        $warningMessage = MessageHandle::get($request->get("mess"));
        return view('Login::index',["warningMessage"=>$warningMessage]);
    }

}
