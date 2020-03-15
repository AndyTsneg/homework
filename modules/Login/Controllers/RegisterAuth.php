<?php

namespace Login\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Login\Exceptions\myException;
use Login\Services\Account;
use Login\Model\RegisterCredential;
use Login\Helper\MessageHandle;

class RegisterAuth extends ALogin implements IHttpAction
{
    /**
     * Authorization for register
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function execute(Request $request)
    {
        try {
            $messKey = '';
            $userInfo       = new RegisterCredential($request);
            $signIn = new Account();

            if($signIn->goRegister($userInfo)){
                $messKey = MessageHandle::put("Successfully");
                return redirect('/login/?mess='.$messKey);
            }

        }catch(myException $ex){
            $messKey = MessageHandle::put($ex->getMessage());
            return redirect('/login/register?mess='.$messKey);
        }catch(\Exception $ex){
            echo $ex->getMessage();
        }
    }
}
