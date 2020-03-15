<?php

namespace Login\Model;

use Login\Exceptions\myException;
use Illuminate\Http\Request;
use Login\Services\Account;

class RegisterCredential implements IRegisterCredential
{
    var $id;
    var $pw;
    var $name;

    public function __construct(Request $request){
        try {
            $this->checkEachField($request);
            $this->checkTwicePassword($request);
            $this->checkUserExist($request);

            $this->id   = $request->input('id');
            $this->pw   = md5($request->input('pw'));
            $this->name = $request->input('name');
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
            'name' => 'required',
            'confirm_pw'=>'required',
        ]);
        if ($validator->fails()) {
            throw myException::setException('1001','ID and PW confirm pw and Name are required');
        }
    }

    /**
     * Checking password are same
     * @param Request $request
     * @throws myException
     */
    public function checkTwicePassword(Request $request){
        $validator = \Validator::make($request->all(), [
            'pw' => 'required|same:confirm_pw',
        ]);
        if ($validator->fails()) {
            throw myException::setException('1005','Please check password are same');
        }
    }

    /**
     * Checking whether id exists
     * @param Request $request
     * @throws myException
     */
    public function checkUserExist(Request $request){
        $signIn = new Account();
        $signIn->checkUserExist($request->get("id"));
    }



}