<?php

namespace Infrastructure\Http;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

use Infrastructure\Exceptions as CustomException;

abstract class ApiRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        if (method_exists($this,'createLog')) {
            $this->createLog();
        }
    	throw new CustomException\GeneralException("IWE004", null, null, [$validator->errors()->first()]);
    }

    protected function failedAuthorization()
    {
        if (method_exists($this,'createLog')) {
            $this->createLog();
        }
        throw new HttpException(403);
    }
    public function validatePassword()
    {
        return 'regex:/^(?=.?[a-z])(?=.*?[0-9])(?!.*?\s)(?!.*À|.*Á|.*Â|.*Ã|.*È|.*É|.*Ê|.*Ì|.*Í|.*Ò|.*Ó|.*Ô|.*Õ|.*Ù|.*Ú|.*Ă|.*Đ|.*Ĩ|.*Ũ|.*Ơ|.*à|.*á|.*â|.*ã|.*è|.*é|.*ê|.*ì|.*í|.*ò|.*ó|.*ô|.*õ|.*ù|.*ú|.*ă|.*đ|.*ĩ|.*ũ|.*ơ|.*Ư|.*Ă|.*Ạ|.*Ả|.*Ấ|.*Ầ|.*Ẩ|.*Ẫ|.*Ậ|.*Ắ|.*Ằ|.*Ẳ|.*Ẵ|.*Ặ|.*Ẹ|.*Ẻ|.*Ẽ|.*Ề|.*Ề|.*Ể|.*ư|.*ă|.*ạ|.*ả|.*ấ|.*ầ|.*ẩ|.*ẫ|.*ậ|.*ắ|.*ằ|.*ẳ|.*ẵ|.*ặ|.*ẹ|.*ẻ|.*ẽ|.*ề|.*ề|.*ể|.*Ễ|.*Ệ|.*Ỉ|.*Ị|.*Ọ|.*Ỏ|.*Ố|.*Ồ|.*Ổ|.*Ỗ|.*Ộ|.*Ớ|.*Ờ|.*Ở|.*Ỡ|.*Ợ|.*Ụ|.*Ủ|.*Ứ|.*Ừ|.*ễ|.*ệ|.*ỉ|.*ị|.*ọ|.*ỏ|.*ố|.*ồ|.*ổ|.*ỗ|.*ộ|.*ớ|.*ờ|.*ở|.*ỡ|.*ợ|.*ụ|.*ủ|.*ứ|.*ừ|.*Ử|.*Ữ|.*Ự|.*Ỳ|.*Ỵ|.*Ý|.*Ỷ|.*Ỹ|.*ử|.*ữ|.*ự|.*ỳ|.*ỵ|.*ỷ|.*ỹ).{8,200}$/';
    }
    public function messageValidatePassword()
    {
        return __('Mật khẩu phải gồm chữ và số, không được phép tồn tại khoảng trắng và dấu Tiếng Việt');
    }
}
