<?php

namespace Login\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Login\Exceptions\myException;
use Login\Helper\MessageHandle;
use Login\Model\LoginCredential;
use Login\Model\RegisterCredential;
use Login\Services\Account;
use mysql_xdevapi\Exception;


class FbAuth extends ALogin implements IHttpAction
{
    /**
     * Authorization for sign in
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function execute(Request $request)
    {
        try {
            $this->goAuth($request); //Go get authorized

            //It means it sign in successfully.
            echo "Hi, ".$_SESSION['user_name'].". Welcome to back<br>";
            echo '<a href="/login/">back</a>';

        }catch(\Exception $ex){
            $messKey = MessageHandle::put($ex->getMessage());
            return redirect('/login/?mess='.$messKey);
        }
    }

    /**
     * @param Request $request
     * @return bool|string
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function goAuth(Request $request)
    {
        if($request->get("error_code")!=''){
            throw new \Exception("Failed to sign in facebook");
        }

        $fb = new \Facebook\Facebook([
            'app_id' => $_ENV['FB_APP_ID'],
            'app_secret' => $_ENV['FB_APP_SECRET'],
            'default_graph_version' => 'v6.0',
        ]);
        $helper = $fb->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
            $response    = $fb->get('/me?fields=id,name', $accessToken);
            $user        = $response->getGraphUser();

            $request->merge(['id'          => $user['id']]);
            $request->merge(['name'        => $user['name']]);
            $request->merge(['pw'          => $user['id']]);
            $request->merge(['confirm_pw'  => $user['id']]);
            $request->merge(['social_type' => 'fb']);

            $signIn = new Account();
            if(!$signIn->checkUserExist($request->get("id"))){
                $userInfo = new RegisterCredential($request);
                if(!$signIn->goRegister($userInfo)){
                    throw myException::setException("9999","Unknown issue");
                }
            }

            //try to go sign in system.
            $userInfo  = new LoginCredential($request);
            if(!$signIn->goSignIn($userInfo)){
                throw myException::setException("1002","Failed to sign in facebook");
            }

        }catch(\Exception $ex){
            throw new \Exception("Failed to sign in facebook");
        }
    }
}
