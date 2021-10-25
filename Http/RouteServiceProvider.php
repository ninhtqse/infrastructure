<?php

namespace Infrastructure\Http;

use Illuminate\Routing\Router;
use Phuongtt\Api\System\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $router = $this->app->make(Router::class);

        $router->pattern('id', '/^(?:(\()|(\{))?[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}(?(1)\))(?(2)\})$/');
        $router->pattern('ids', '/(^(?:(\()|(\{))?[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}(?(1)\))(?(2)\})$)?,/');

        parent::boot($router);
    }
}
