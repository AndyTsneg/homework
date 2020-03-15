<?php

namespace Login\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Login\Exceptions\myException;
use Login\Services\Account;
use Login\Model\LoginCredential;
use Login\Helper\MessageHandle;

class LoginAuth extends ALogin implements IHttpAction
{
    /**
     * Authorization for sign in
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function execute(Request $request)
    {
        try {
            $this->goAuth($request); // Go get authorized

            //It means it sign in successfully.
            echo "Hi, ".$_SESSION['user_name'].". Welcome to back<br>";
            echo '<a href="./">back</a>';

        }catch(myException $ex){
            $messKey = MessageHandle::put($ex->getMessage());
            return redirect('/login/?mess='.$messKey);
        }catch(\Exception $ex){
            echo $ex->getMessage();
        }
    }

    /**
     * @param Request $request
     * @return mixed|void
     * @throws myException
     */
    public function goAuth(Request $request)
    {
        try{
            $userInfo  = new LoginCredential($request);
            $signIn    = new Account();
            if(!$signIn->goSignIn($userInfo)){
                throw myException::setException("1002","ID or PW is wrong");
            }
        }catch(\Exception $ex){
            throw myException::setException($ex->getCode(),$ex->getMessage());
        }
    }
}
