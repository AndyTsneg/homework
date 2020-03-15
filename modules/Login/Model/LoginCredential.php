<?php

namespace Login\Model;

use Login\Exceptions\myException;
use Illuminate\Http\Request;
use Login\Model\ILoginCredential;

class LoginCredential implements ILoginCredential
{
    var $id;
    var $pw;

    public function __construct(Request $request){
        try {
            $this->checkEachField($request);
            $this->id = $request->input('id');
            $this->pw = md5($request->input('pw'));
        }catch(myException $ex){
            $code = $ex->getCode();
            $mess = $ex->getMessage();
            throw myException::setException($code,$mess);
        }catch(\Exception $ex){
            //Unknown error. Should be logging here.
        }
    }

    /**
     * Validate each field is correct
     * @param Request $request
     * @throws myException
     */
    public function checkEachField(Request $request){
        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'pw' => 'required',
        ]);
        if ($validator->fails()) {
            throw myException::setException('1001','ID and PW are required');
        }
    }
}