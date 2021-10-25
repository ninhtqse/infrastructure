<?php

namespace Infrastructure\Api\Controllers;

use Infrastructure\Http\Controller as BaseController;
use Infrastructure\Libraries\HelperFunction;
use Illuminate\Foundation\Application;
use GuzzleHttp\Client;
use Config;
use DB;

class DefaultApiController extends BaseController
{
    private $cookie;
    
    private $helperFunction;

    public function __construct(
        Application $app,
        HelperFunction $helperFunction
    ) {
        $this->cookie            = $app->make('cookie');
        $this->helperFunction    = $helperFunction;
    }
    public function index()
    {
        return response()->json([
            'title'   => 'api-base',
            'version' => '3.0'
        ]);

    }
}
