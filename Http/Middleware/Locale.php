<?php

namespace Infrastructure\Http\Middleware;

use Closure;
use Infrastructure\Exceptions as EfyException;

class Locale
{
    public function __construct() {
    }

    public function handle($request, Closure $next)
    {
        \App::setLocale(@$request->header()['lang'][0]);
        return $next($request);
    }
}
