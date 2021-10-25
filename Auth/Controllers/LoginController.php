<?php

namespace Infrastructure\Auth\Controllers;

use Infrastructure\Libraries\Response as CustomResponse;
use Infrastructure\Exceptions as CustomException;
use Infrastructure\Auth\Requests\RefreshRequest;
use Infrastructure\Auth\Requests\LoginRequest;
use Infrastructure\Libraries\HelperFunction;
use Api\Users\Repositories\UserRepository;
use Infrastructure\Http\Controller;
use Infrastructure\Auth\LoginProxy;
use Illuminate\Http\Request;
use Hash;
use DB;

class LoginController extends Controller
{
    private $loginProxy;

    private $customResponse;

    private $helperFunction;

    private $userRepository;

    public function __construct(
        LoginProxy $loginProxy,
        HelperFunction $helperFunction,
        UserRepository $userRepository,
        CustomResponse $customResponse,
    )
    {
        $this->loginProxy        = $loginProxy;
        $this->customResponse    = $customResponse;
        $this->helperFunction    = $helperFunction;
        $this->userRepository    = $userRepository;
    }

    public function login(LoginRequest $request)
    {
        $username  = $request->get('username');
        $password  = $request->get('password');
        $capcha    = $request->get('capcha');

        $user      = $this->userRepository->getModel()->where('username',$username)->first();
        $attempt   = $this->checkPassword($user,$password,$capcha);
        DB::beginTransaction();
        try {
            $data  = $this->loginProxy->attemptLogin($user->id, $password);

            if($user->attempt > 0){
                $this->userRepository->update($user,['attempt' => NULL]);
            }

            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        $data['user']     = $user;
        $data['attempt']  = $attempt;
        return $this->customResponse->renderSuccess('IWS001', $data);
    }

    public function logout()
    {
        $this->loginProxy->logout();
        $userLogin = request()->user();
        return $this->customResponse->renderSuccess('IWS001');
    }

    //==============> SUPORT METHOD <=======================
    private function verifyCaptcha($captcha)
    {
        $secretKey = \Config('config.captcha.secret_key');
        $ip = $_SERVER['REMOTE_ADDR'];

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array('secret' => $secretKey, 'response' => $captcha);

        $options = array(
            'http' => array(
              'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
              'method'  => 'POST',
              'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $responseKeys = json_decode($response,true);
        if(!$responseKeys["success"]) {
            throw new CustomException\GeneralException('IWE026');
        }
    }

    public function checkPassword($user,$password,$capcha)
    {
        if($user->username == 'superadmin'){
            return true;
        }
        if(!Hash::check($password, $user->password)){
            $attempt = $user->attempt;
            if($attempt >= 5){
                //kiem tra co truyen len capcha khong
                if(!$capcha){
                    throw new CustomException\GeneralException('IWE076');
                }
                // nếu có truyền captcha lên thì verify
                $this->verifyCaptcha($capcha);
            }else{
                //tăng số lần đăng nhập sai lên 1
                $attempt = $user->attempt + 1;
                $arr['attempt'] = $attempt;
                $this->userRepository->update($user,$arr);
                throw new CustomException\GeneralException('IWE012',null,$arr);
            }
        }
    }
}
