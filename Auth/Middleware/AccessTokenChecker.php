<?php

namespace Infrastructure\Auth\Middleware;

use Infrastructure\Exceptions as CustomException;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\AuthenticationException;
use Infrastructure\Libraries\HelperFunction;
use Illuminate\Foundation\Application;
use Closure;

class AccessTokenChecker
{
    private $app;

    private $helperFunction;

    public function __construct(
        Application $app,
        Authenticate $authenticate,
        HelperFunction $helperFunction
    ) {
        $this->app = $app;
        $this->authenticate = $authenticate;
        $this->helperFunction = $helperFunction;
    }

    public function handle($request, Closure $next, $scopesString = null)
    {
        if ($this->app->environment() !== 'testing') {
            try {
                return $this->authenticate->handle($request, $next, 'api');
            } catch (AuthenticationException $e) {
                throw new CustomException\GeneralException('IWE008');
            }
        }

        return $next($request);
    }
}
