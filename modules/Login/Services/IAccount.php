<?php

namespace Login\Services;

use Login\Model\LoginCredential;
use Login\Model\RegisterCredential;

    /**
     * Interface for checking sing in.
     */
interface IAccount
{
    public function goSignIn(LoginCredential $userInfo):bool ;
    public function writeToSession($userId,$userName):bool;
    public function goRegister(RegisterCredential $userInfo):bool;
    public function checkUserExist($userId):bool;
}
