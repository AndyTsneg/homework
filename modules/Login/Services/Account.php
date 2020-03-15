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
     * @throws myException
     */
    public function goSignIn(LoginCredential $userInfo):string
    {
        $users = Member::whereRaw('user_id = :id and password = :pw', [":id" => $userInfo->id, ":pw" => $userInfo->pw])->get();
        if (count($users)>0){
            $this->writeToSession($users[0]->user_id);
            return $users[0]->user_id;
        }else{
            throw myException::setException("1002","ID or PW is wrong");
        }
    }

    /**
     * Obviously just checking whether user exists
     *
     * @param $userId
     * @throws myException
     */
    public function checkUserExist($userId)
    {
        $users = Member::whereRaw('user_id = :id', [":id" => $userId])->get();
        if (count($users)>0){
            throw myException::setException("1003","Duplicate user id. Please try again");
        }
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
            $member->user_id    = $userInfo->id;
            $member->password   = $userInfo->pw;
            $member->name       = $userInfo->name;
            $member->save();
            return true;
        }
        catch(\Exception $ex)
        {
            throw myException::setException("500","db error:".$ex->getMessage());
        }
    }


    /**
     * After user sign in, need to put user data to session
     *
     * @param $userId
     * @return bool
     */
    public function writeToSession($userId):bool
    {
        //Should store to session
        return true;
    }
}
