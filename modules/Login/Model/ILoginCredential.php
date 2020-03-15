<?php

namespace Login\Model;

use Illuminate\Http\Request;


/**
 * Interface for sign in validator.
 *
 */
interface ILoginCredential
{
    /**
     * Validate each field is correct
     * @param Request $request
     */
    public function checkEachField(Request $request);
}