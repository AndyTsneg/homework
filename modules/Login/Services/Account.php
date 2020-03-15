<?php

namespace Login\Services;


use Login\Exceptions\myException;
use Login\Model\LoginCredential;
use Login\Model\RegisterCredential;
use Login\Model\Member;


class Account  implements IAccount
{
    /**
     * authorize id and password
     *
     * @param LoginCredential $userInfo
     * @return string
     */
    public function goSignIn(LoginCredential $userInfo):bool
    {
        $users = Member::whereRaw('user_id = :id and password = :pw', [":id" => $userInfo->id, ":pw" => $userInfo->pw])->get();
        if (count($users)>0){
            $this->writeToSession($users[0]->user_id,$users[0]->name);
            return true;
        }else{
            return false;
        }
        return false;
    }

    /**
     * Obviously just checking whether user exists
     *
     * @param $userId
     * @return bool
     */
    public function checkUserExist($userId):bool
    {
        $users = Member::whereRaw('user_id = :id', [":id" => $userId])->get();
        if (count($users)>0){
            return true;
        }
        return false;
    }

    /**
     * Go to register an account for end-user
     *
     * @param RegisterCredential $userInfo
     * @return bool
     * @throws myException
     */
    public function goRegister(RegisterCredential $userInfo):bool
    {
        try{
            $member = new Member;
            $member->user_id     = $userInfo->id;
            $member->password    = $userInfo->pw;
            $member->name        = $userInfo->name;
            $member->social_type = $userInfo->socialType;
            $member->save();
            return true;
        }
        catch(\Exception $ex)
        {
            throw myException::setException("500","db error:".$ex->getMessage());
        }
        return false;
    }


    /**
     * After user sign in, need to put user data to session
     *
     * @param $userId
     * @param $userName
     * @return bool
     */
    public function writeToSession($userId,$userName):bool
    {
        $_SESSION['user_id']   = $userId;
        $_SESSION['user_name'] = $userName;
        return true;
    }
}
