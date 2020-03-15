<?php

namespace Login\Model;

use Illuminate\Http\Request;


/**
 * Interface for sign in validator.
 *
 */
interface IRegisterCredential
{
    /**
     * Validate each field is correct
     * @param Request $request
     */
    public function checkEachField(Request $request);

    /**
     * Password confirmation validation
     *
     * @param Request $request
     * @return mixed
     */
    public function checkTwicePassword(Request $request);


}