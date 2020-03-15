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


class GoogleAuth extends ALogin implements IHttpAction
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
     * @throws \Exception
     */
    public function goAuth(Request $request)
    {
        try {
            $client = new \Google_Client;
            $client->setClientId($_ENV['GOOGLE_APP_ID']);
            $client->setClientSecret($_ENV['GOOGLE_APP_Secret']);


            if (isset($_GET['code']))
            {
                $client->setRedirectUri($_ENV['GOOGLE_AUTH_URL']);
                $result = $client->authenticate($_GET['code']);

                if (isset($result['error']))
                {
                    throw myException::setException("1002","Failed to sign in google");
                }

                $_SESSION['google']['access_token'] = $result;
                header("Location:".$_ENV['GOOGLE_AUTH_URL']."?action=profile");
                exit;
            }
            elseif ($_GET['action'] == "profile")
            {
                $profile = $client->verifyIdToken($_SESSION['google']['access_token']['id_token']);

                $request->merge(['id'          => $profile['sub']]);
                $request->merge(['name'        => $profile['name']]);
                $request->merge(['pw'          => $profile['sub']]);
                $request->merge(['confirm_pw'  => $profile['sub']]);
                $request->merge(['social_type' => 'google']);

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
                    throw myException::setException("1002","Failed to sign in google");
                }

            }
        }catch(\Exception $ex){
            throw new \Exception("Failed to sign in google");
        }
    }
}
