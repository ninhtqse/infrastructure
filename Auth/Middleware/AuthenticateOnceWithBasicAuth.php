<?php

namespace Infrastructure\Auth\Middleware;

use Infrastructure\Exceptions as CustomException;
use Infrastructure\Libraries\HelperFunction;
use Illuminate\Support\Facades\Auth;

class AuthenticateOnceWithBasicAuth
{

    private $helperFunction;

    public function __construct(
        HelperFunction $helperFunction
    ) {
        $this->helperFunction = $helperFunction;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        $requestUsername = (@$request->header()['php-auth-user'][0]) ? $request->header()['php-auth-user'][0] : '';
        $requestPassword = (@$request->header()['php-auth-pw'][0]) ? $request->header()['php-auth-pw'][0] : '';
        $basicAuth = \config('config.basic_auth');
        foreach($basicAuth as $value) {
            if($value['username'] == $requestUsername && $value['password'] == $requestPassword) {
                return $next($request);
            }
        }
        throw new CustomException\GeneralException('IWE008');
    }
}
