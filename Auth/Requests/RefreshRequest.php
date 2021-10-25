<?php

namespace Infrastructure\Auth\Requests;

use Infrastructure\Http\ApiRequest;

class RefreshRequest extends ApiRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'refresh_token' => 'required|string',
        ];
    }
    
    public function attributes()
    {
        return [
            'refresh_token'        => 'Refresh token',
        ];
    }
}
