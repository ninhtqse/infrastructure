<?php

namespace Infrastructure\Auth\Requests;

use Infrastructure\Http\ApiRequest;

class LoginRequest extends ApiRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tax_code'         => 'required|max:45',
            'username'         => 'required|max:50',
            'password'         => 'required_if:token_login_once,=,""|min:6|regex:/^([\w_\.!@#$%^&*()]+){5,31}$/',
        ];
    }
    
    public function attributes()
    {
        return [
            'tax_code'        => __('Mã số thuế'),
            'username'        => __('Tên đăng nhập'),
            'password'        => __('Mật khẩu'),
        ];
    }
}
