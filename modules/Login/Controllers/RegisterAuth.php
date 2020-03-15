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
            $this    -> goAuth($request);
            $signIn   = new Account();
            $userInfo = new RegisterCredential($request);
            if($signIn->goRegister($userInfo)){
                $messKey = MessageHandle::put("Successfully");
                return redirect('/login/?mess='.$messKey);
            }else{
                throw myException::setException("9999","Unknown issue");
            }

        }catch(myException $ex){
            $messKey = MessageHandle::put($ex->getMessage());
            return redirect('/login/register?mess='.$messKey);
        }catch(\Exception $ex){
            echo $ex->getMessage();
        }
    }

    public function goAuth(Request $request)
    {
        $signIn = new Account();
        if($signIn->checkUserExist($request->get("id"))){
            throw myException::setException("1003","Duplicate user id. Please try again");
        }
    }
}
