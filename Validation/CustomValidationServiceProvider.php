<?php

namespace Infrastructure\Validation;

use Illuminate\Support\ServiceProvider;
use Validator;

class CustomValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('uuid', function ($attribute, $value, $parameters, $validator) {
            return (bool) preg_match('/^(?:(\()|(\{))?[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}(?(1)\))(?(2)\})$/', $value);
        });
        Validator::extend('datetime', function ($attribute, $value, $parameters, $validator) {
            return (bool) preg_match('/^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)\s([01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9](\.\d{3})?$/', $value);
        });
        Validator::extend("emails", function($attribute, $value, $parameters) {
            $arr = explode(';', $value);
            foreach ($arr as $email) {
                if(!$email || !$this->isEmail($email)) {
                    return false;
                }
            }
            return true;
        });
    }
    private function isEmail($str)
    {
        $reg = "/^[a-zA-Z0-9\.\-\_]*@[a-zA-Z0-9\.\-]*[.][a-zA-Z]*$/";
        if(preg_match($reg,$str)) {
            return true;
        } else {
            return false;
        }
    }
}
