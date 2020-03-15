<?php

namespace Login\Services;

use Login\Model\LoginCredential;
use Login\Model\RegisterCredential;

    /**
     * Interface for checking sing in.
     */
interface IAccount
{
    public function goSignIn(LoginCredential $userInfo);
    public function writeToSession($userId);
    public function goRegister(RegisterCredential $userInfo);
    public function checkUserExist($userId);
}
